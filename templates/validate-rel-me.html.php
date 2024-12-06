<div id="validate-rel-me" class="row demo-row">
    <div class="span12">

    <h1><span class="fui-user"></span> Become a citizen of the IndieWeb <small>Level 1</small></h1>

    <h2>1. Get your own domain name</h2>
    
    <p class="lead">A personal <b>domain name</b> is an inexpensive, internationally universal identifier which gives you more control over your space than other IDs (e.g. email address or phone number.)</p>
    
    <p>On the wiki: <a href="https://indieweb.org/personal-domain">Personal Domains</a>.</p>
    
    <h2>2. Set up Web Sign In</h2>
    
    <p class="lead">In order to be able to sign in using your domain name, connect it to your existing identities.</p>
    
    <p>You probably already have many disconnected profiles on the web. Linking between them and your domain name with the <a href="http://microformats.org/wiki/rel-me"><code>rel=me</code></a> microformat ensures that it’s easy to see that you on Google/Twitter/Github/Flickr/Facebook/email are all the same person as your domain name.</p>
    
    <p>On the wiki: <a href="https://indieweb.org/How_to_set_up_web_sign-in_on_your_own_domain">How to set up Web Sign In</a>.</p>
    
    <?php if ($error or $rels) : ?>
    <div class="result alert <?php if ($error) : ?>alert-warning<?php else: ?>alert-success<?php 
   endif ?>">
        <?php if ($error) : ?>
        <h4>Something Went Wrong!</strong></h4>
        <p>When fetching <code><?php echo $url ?></code>, we got this problem:</p>
        <p><?php echo $error['message'] ?></p>
        <?php elseif ($rels) : ?>
        
        <p>We found the following <code>rel=me</code> URLs on <a class="results-url" href="<?php echo $url ?>">your site</a>:</p>
        
        <ul>
            <?php foreach ($rels as $rel): ?>
            <li class="rel-me-result"><a href="<?php echo $rel ?>"><?php echo $rel ?></a></li>
            <?php endforeach ?>
        </ul>
        <?php endif ?>
        
        <?php echo $render('silo-hint.html', array('url' => $url)) ?>
    </div>
    <?php endif ?>
    
    <form class="row" action="/validate-rel-me/" method="get">
        <div class="span4">
            <input class="span4" type="text" value="<?php echo $url ?>" name="url" placeholder="http://yoursite.com" />
        </div>
        <div class="span3">
            <button type="submit" class="btn btn-large btn-block btn-primary">Test</button>
        </div>
    </form>
    
    <small>Want to be able to use rel-me data in your code? Check out the open source <a href="https://indieweb.org/rel-me#Implementations">implementations</a>.</small>

    <?php if (empty($composite_view)) : ?>
        <hr />
        <p> <a href="/">Home</a> | <a href="/validate-h-card/">Next Step</a> </p>
    <?php endif ?>

    </div><!--/.span-->
</div> <!-- #validate-rel-me -->

