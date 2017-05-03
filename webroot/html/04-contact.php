<!doctype html>
<html lang="nl" class="no-js">
<!--<![endif]-->
<head>
    <?php include_once('blocks/head.html'); ?>
</head>
<body>

<?php include_once('blocks/sidebuttons.html'); ?>
<div class="navigation navigation__slide-down">
    <?php include_once('blocks/header.html'); ?>
</div>

<?php include_once('blocks/breadcrumbs.html'); ?>

<section>
    <div class="wrapper">
        <div class="innner">
            <div class="block block--six block--six__center text-center">
                <h1>Neem contact op</h1>
                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Quod distinctio aut reiciendis, officiis
                    accusamus iusto vitae saepe aliquam dolores repellat in qui at modi a impedit labore, fugiat. Ad,
                    esse!</p>
                <div class="btn--vacature">
                    <span data-link="#contact-form" class="btn btn-primary">Contactformulier</span>
                </div>
                <p class="address">
                    <span>Zoom 1</span>
                    <span>9231 DX Surhuisterveen</span>
                    <span>Achtkarspelen, Friesland</span>
                    <a href="https://www.google.com/maps?f=d&amp;daddr=Zoom+1,Surhuisterveen" title="Route plannen" target="_blank">Route plannen</a>
                </p>
            </div>
            <div class="clearfix"></div>

            <?php include_once('blocks/maps.html'); ?>

            <div id="contact-form">
                <div class="block text-center">
                    <h2>Contactformulier</h2>
                </div>
                <div class="block block--six block--six__center">
                    <form class="form-horizontal" role="form">
                        <div class="form-group has-error">
                            <input type="text" class="form-control" id="name" placeholder="Naam *" autocomplete="name">
                            <p class="help-block">Je bent vergeten je naam in te vullen</p>
                        </div>
                        <div class="form-group">
                            <input type="email" class="form-control" id="email" placeholder="E-mailadres *"
                                   autocomplete="email">
                        </div>
                        <div class="form-group">
                            <input type="tel" class="form-control" id="telefoon" placeholder="Telefoonnummer"
                                   autocomplete="tel">
                        </div>
                        <div class="form-group">
                            <input type="text" class="form-control" id="onderwerp" placeholder="Onderwerp *">
                        </div>
                        <div class="form-group">
                            <textarea name="message" id="message" cols="30" rows="10" class="form-control"></textarea>
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">Versturen</button>
                        </div>
                    </form>
                </div>
                <div class="clearfix"></div>
            </div>

        </div>
    </div>
</section>

<?php include_once('blocks/footer.html'); ?>
<script src="/assets/js/google-maps.js"></script>
</body>
</html>