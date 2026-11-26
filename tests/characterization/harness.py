#!/usr/bin/env python3
"""Characterization harness: record the app's HTTP behaviour, and diff two recordings.

  harness.py record --php php7.4 --app /path/to/checkout --out /path/to/rec-74
  harness.py compare /path/to/rec-74 /path/to/rec-86 [--show N]

`record` starts two servers:
  * the fixture "internet" (tests/characterization/fixtures.php), always on
    FIXTURE_PHP (default php8.6) so it is identical whichever PHP runs the app;
  * the app under test, `<php> -S` with web/index.php as router.
It then sends every request in requests.txt (relative to this file) and saves
status, headers (minus Date / X-Powered-By), body, and the outbound requests
the fixture server saw while that request was being handled.

requests.txt lines:  METHOD /path?query   [| urlencoded form body]
`{F}` is replaced by the fixture base URL (http://127.0.0.1:<fixture-port>).
Outbound traffic only ever goes to that fixture server, so POSTs are safe.
Never add inputs that resolve to a real host: e.g. cassis turns `@name` into
https://twitter.com/name, and `.invalid` hostnames are the only safe
non-fixture hosts.
"""
import argparse, json, os, re, shutil, subprocess, sys, time, urllib.error, urllib.parse, urllib.request

HERE = os.path.dirname(os.path.abspath(__file__))
MARKER = '.harness-recording'
DROP_HEADERS = {'date', 'x-powered-by', 'connection', 'host'}


class NoRedirect(urllib.request.HTTPRedirectHandler):
    def redirect_request(self, *a, **k):
        return None


def wait_port(port, timeout=10):
    import socket
    end = time.time() + timeout
    while time.time() < end:
        try:
            socket.create_connection(('127.0.0.1', port), 0.2).close()
            return
        except OSError:
            time.sleep(0.05)
    sys.exit(f'port {port} never opened')


def slug(i, method, path):
    return f'{i:03d}-{method}-' + re.sub(r'[^A-Za-z0-9]+', '-', path).strip('-')[:90]


def record(a):
    if not a.app.strip() or not a.out.strip():
        sys.exit('refusing: --app and --out must be non-empty')
    app = os.path.abspath(a.app)
    if not os.path.isfile(os.path.join(app, 'web', 'index.php')):
        sys.exit(f'refusing: {app} has no web/index.php (not an indiewebify-me checkout?)')
    out = os.path.abspath(a.out)
    if os.path.exists(out):
        if not os.path.isfile(os.path.join(out, MARKER)):
            sys.exit(f'refusing: {out} exists and is not a previous recording')
        shutil.rmtree(out)
    os.makedirs(out)
    open(os.path.join(out, MARKER), 'w').write(f'{a.php} {app}\n')

    flog = os.path.join(out, 'fixture-requests.jsonl')
    open(flog, 'w').close()
    fbase = f'http://127.0.0.1:{a.fixture_port}'
    env = dict(os.environ, FIXTURE_LOG=flog)
    procs = [
        subprocess.Popen([a.fixture_php, '-S', f'127.0.0.1:{a.fixture_port}', os.path.join(HERE, 'fixtures.php')],
                         env=env, stdout=subprocess.DEVNULL, stderr=subprocess.DEVNULL),
        subprocess.Popen([a.php, '-d', 'short_open_tag=Off', '-d', 'display_errors=0', '-d', 'log_errors=1',
                          '-d', 'error_reporting=-1', '-d', 'error_log=' + os.path.join(out, 'php-errors.log'),
                          '-S', f'127.0.0.1:{a.app_port}', '-t', os.path.join(app, 'web'),
                          os.path.join(app, 'web', 'index.php')],
                         cwd=app, stdout=subprocess.DEVNULL, stderr=open(os.path.join(out, 'app-server.log'), 'w')),
    ]
    try:
        wait_port(a.fixture_port)
        wait_port(a.app_port)
        opener = urllib.request.build_opener(NoRedirect)
        timings = []
        lines = [l.rstrip('\n') for l in open(os.path.join(HERE, 'requests.txt'))
                 if l.strip() and not l.lstrip().startswith('#')]
        for i, line in enumerate(lines):
            line = line.replace('{F}', fbase)
            req_part, _, form = line.partition(' | ')
            method, path = req_part.split(None, 1)
            data = form.encode() if form else None
            headers = {'Content-Type': 'application/x-www-form-urlencoded'} if form else {}
            req = urllib.request.Request(f'http://127.0.0.1:{a.app_port}{path}', data=data, method=method, headers=headers)
            pos = os.path.getsize(flog)
            t = time.monotonic()
            try:
                r = opener.open(req, timeout=60)
                status, body, hdrs = r.status, r.read(), r.headers
            except urllib.error.HTTPError as e:
                status, body, hdrs = e.code, e.read(), e.headers
            ms = round((time.monotonic() - t) * 1000, 1)
            with open(flog) as f:
                f.seek(pos)
                outbound = [json.loads(x) for x in f.read().splitlines() if x]
            name = slug(i, method, path)
            hdr_lines = sorted(f'{k.lower()}: {v}' for k, v in hdrs.items() if k.lower() not in DROP_HEADERS)
            with open(os.path.join(out, name + '.txt'), 'wb') as f:
                f.write(f'# {method} {path}\n'.encode())
                if form:
                    f.write(f'# form: {form}\n'.encode())
                f.write(f'status: {status}\n'.encode())
                f.write(('\n'.join(hdr_lines) + '\n').encode())
                f.write(b'---- outbound ----\n')
                for o in outbound:
                    f.write((json.dumps(o, sort_keys=True, ensure_ascii=False) + '\n').encode())
                f.write(b'---- body ----\n')
                f.write(body)
            timings.append({'request': line, 'status': status, 'ms': ms})
        json.dump(timings, open(os.path.join(out, 'timings.json'), 'w'), indent=1)
        print(f'recorded {len(lines)} requests to {out}')
    finally:
        for p in procs:
            p.terminate()
            p.wait()


def compare(a):
    names = sorted(set(os.listdir(a.a)) | set(os.listdir(a.b)))
    names = [n for n in names if n.endswith('.txt') and n[0].isdigit()]
    # Default masks: the h-entry template prints the current time as an example.
    masks = [re.compile(r) for r in [r'\d{4}-\d\d-\d\d \d\d:\d\d:\d\d\+0000'] + (a.ignore or [])]
    import difflib
    same = changed = 0
    shown = 0
    for n in names:
        try:
            x = open(os.path.join(a.a, n), 'rb').read().decode('utf-8', 'replace')
            y = open(os.path.join(a.b, n), 'rb').read().decode('utf-8', 'replace')
        except FileNotFoundError:
            print(f'MISSING in one side: {n}')
            changed += 1
            continue
        for m in masks:
            x, y = m.sub('<masked>', x), m.sub('<masked>', y)
        if x == y:
            same += 1
            continue
        changed += 1
        if shown < a.show:
            shown += 1
            print(f'==== {n}')
            sys.stdout.writelines(difflib.unified_diff(x.splitlines(True), y.splitlines(True), 'a', 'b', n=1))
    print(f'\nidentical: {same}  changed: {changed}  total: {len(names)}')
    return 1 if changed else 0


def main():
    p = argparse.ArgumentParser()
    s = p.add_subparsers(dest='cmd', required=True)
    r = s.add_parser('record')
    r.add_argument('--php', required=True)
    r.add_argument('--app', required=True)
    r.add_argument('--out', required=True)
    r.add_argument('--app-port', type=int, default=8301)
    r.add_argument('--fixture-port', type=int, default=8290)
    r.add_argument('--fixture-php', default=os.environ.get('FIXTURE_PHP', 'php8.6'))
    c = s.add_parser('compare')
    c.add_argument('a')
    c.add_argument('b')
    c.add_argument('--show', type=int, default=50)
    c.add_argument('--ignore', action='append')
    a = p.parse_args()
    sys.exit(record(a) if a.cmd == 'record' else compare(a))


if __name__ == '__main__':
    main()
