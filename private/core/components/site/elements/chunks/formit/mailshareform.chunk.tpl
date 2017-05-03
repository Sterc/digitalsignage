<div id="mail-popup" class="social-mail-popup">
    <h2>{$_modx->lexicon('site.form.share.emailtitle')}</h2>

    {$_modx->runSnippet('FormIt', [
        'hooks'            => 'spam,email,redirect',
        'emailTo'          => $.post['email'],
        'emailReplyTo'     => $email,
        'emailReplyToName' => $_modx->config.site_name,
        'emailSubject'     => $_modx->lexicon('site.form.share.emailsubject'),
        'emailFromName'    => $_modx->config.site_name,
        'emailFrom'        => $email,
        'emailTpl'         => 'shareFormTpl',

        'formName'         => 'Deel deze pagina',
        'formFields'       => 'name,email,message,shareUrl',
        'fieldNames'       => 'name==Naam,email==E-mail,message==Bericht,shareUrl==Url',

        'redirectTo'       => $_modx->config.page_share_thanks,
        'validate'         => 'email:email:required,message:required,nospam:blank',
        'submitVar'        => 'mailshare-submit'
    ])}

    <form action="{$_modx->makeUrl($_modx->resource.id)}#mail-popup" role="form" method="post" novalidate>
        <input type="hidden" name="nospam" value=""/>
        <input type="hidden" name="shareUrl" value="{$_modx->makeUrl($_modx->resource.id, '', '', 'full')}" />


        <div class="form-group {$_modx->getPlaceholder('fi.error.name') == null ? '' : 'has-error'}">
            <label for="name">{$_modx->lexicon('site.form.name')} *</label>
            <input class="form-control" type="text" value="{$_modx->getPlaceholder('fi.name')}" name="name" id="fullname" autocomplete="name"/>
            {$_modx->getPlaceholder('fi.error.name') == null ? '' : '<p class="help-block">' ~ $_modx->lexicon("site.form.error.name") ~ '</p>'}
        </div>

        <div class="form-group {$_modx->getPlaceholder('fi.error.email') == null ? '' : 'has-error'}">
            <label for="email">{$_modx->lexicon('site.form.email')} *</label>
            <input class="form-control" type="email" value="{$_modx->getPlaceholder('fi.email')}" name="email" id="email" autocomplete="email"/>
            {$_modx->getPlaceholder('fi.error.email') == null ? '' : '<p class="help-block">' ~ $_modx->lexicon("site.form.error.email") ~ '</p>'}
        </div>

        <div class="form-group {$_modx->getPlaceholder('fi.error.message') == null ? '' : 'has-error'}">
            <label for="messagearea">{$_modx->lexicon('site.form.message')} *</label>
            <textarea class="form-control" rows="4" name="message" id="messagearea">{$_modx->getPlaceholder('fi.message')}</textarea>
            {$_modx->getPlaceholder('fi.error.message') == null ? '' : '<p class="help-block">' ~ $_modx->lexicon("site.form.error.message") ~ '</p>'}
        </div>

        <div class="form-group">
            <p class="help-block">* {$_modx->lexicon('site.form.requiredfields')}</p>
        </div>

        <input type="submit" class="btn btn-primary" name="mailshare-submit" value="{$_modx->lexicon('site.form.submit')}"/>
    </form>
</div>