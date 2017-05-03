{$_modx->runSnippet('!FormIt', [
    'hooks'            => 'spam,FormItSaveForm,email,FormItAutoResponder,redirect',
    'emailTo'          => $_modx->config.email_client,
    'emailReplyTo'     => $email,
    'emailReplyToname' => $_modx->config.site_name,
    'emailBCC'         => $_modx->config.email_bcc,
    'emailBCCName'     => 'Contactformulier BCC',
    'emailSubject'     => $_modx->lexicon('site.form.contact.emailsubject'),
    'emailFromName'    => $name,
    'emailFrom'        => $email,
    'emailTpl'         => 'contactFormTpl',
    
    'formName'         => 'Contactformulier',
    'formFields'       => 'name,fname,lname,email,phone,address,zip,city,message',
    'fieldNames'       => 'name==Naam,fname==Voornaam,lname==Achternaam,email==E-mail,phone==Telefoon,address==Straat,zip==Postcode,city==Plaats,message==Bericht',
    
    'fiarToField'      => 'email',
    'fiarTpl'          => 'contactFormFiarTpl',
    'fiarSubject'      => $_modx->lexicon('site.form.contact.emailsubject'),
    'fiarFrom'         => $_modx->config.email_client,
    'fiarFromName'     => $_modx->config.site_name,
    'fiarReplyTo'      => $_modx->config.email_client,
    
    'redirectTo'       => $_modx->config.page_contact_thanks,
    'validate'         => 'name:required,fname:required,lname:required,email:email:required,phone:required,address:required,zip:required,city:required,message:required,nospam:blank',
    'store'            => 1,
    'submitVar'        => 'contact-submit'
])}

<div id="contactform">
    <form action="{$_modx->makeUrl($_modx->resource.id)}" role="form" method="post" novalidate>
        <input type="hidden" name="nospam" value="" />

        <div class="form-group {$_modx->getPlaceholder('fi.error.name') == null ? '' : 'has-error'}">
            <label for="fullname">{$_modx->lexicon('site.form.name')} *</label>
            <input class="form-control" type="text" value="{$_modx->getPlaceholder('fi.name')}" name="name" id="fullname" autocomplete="name"/>
            {$_modx->getPlaceholder('fi.error.name') == null ? '' : '<p class="help-block">' ~ $_modx->lexicon("site.form.error.name") ~ '</p>'}
        </div>
        
        <div class="form-group {$_modx->getPlaceholder('fi.error.fname') == null ? '' : 'has-error'}">
            <label for="fname">{$_modx->lexicon('site.form.fname')} *</label>
            <input class="form-control" type="text" value="{$_modx->getPlaceholder('fi.fname')}" name="fname" id="fname" autocomplete="given-name"/>
            {$_modx->getPlaceholder('fi.error.fname') == null ? '' : '<p class="help-block">' ~ $_modx->lexicon("site.form.error.fname") ~ '</p>'}
        </div>

        <div class="form-group {$_modx->getPlaceholder('fi.error.lname') == null ? '' : 'has-error'}">
            <label for="lname">{$_modx->lexicon('site.form.lname')} *</label>
            <input class="form-control" type="text" value="{$_modx->getPlaceholder('fi.lname')}" name="lname" id="lname" autocomplete="family-name"/>
            {$_modx->getPlaceholder('fi.error.lname') == null ? '' : '<p class="help-block">' ~ $_modx->lexicon("site.form.error.lname") ~ '</p>'}
        </div>

        <div class="form-group {$_modx->getPlaceholder('fi.error.email') == null ? '' : 'has-error'}">
            <label for="email">{$_modx->lexicon('site.form.email')} *</label>
            <input class="form-control" type="email" value="{$_modx->getPlaceholder('fi.email')}" name="email" id="email" autocomplete="email"/>
            {$_modx->getPlaceholder('fi.error.email') == null ? '' : '<p class="help-block">' ~ $_modx->lexicon("site.form.error.email") ~ '</p>'}
        </div>
          
        <div class="form-group {$_modx->getPlaceholder('fi.error.phone') == null ? '' : 'has-error'}">
            <label for="phone">{$_modx->lexicon('site.form.phone')} *</label>
            <input class="form-control" type="tel" value="{$_modx->getPlaceholder('fi.phone')}" name="phone" id="phone" autocomplete="tel"/>
            {$_modx->getPlaceholder('fi.error.phone') == null ? '' : '<p class="help-block">' ~ $_modx->lexicon("site.form.error.phone") ~ '</p>'}
        </div>

        <div class="form-group {$_modx->getPlaceholder('fi.error.address') == null ? '' : 'has-error'}">
            <label for="address">{$_modx->lexicon('site.form.address')} *</label>
            <input class="form-control" type="text" value="{$_modx->getPlaceholder('fi.address')}" name="address" id="address" autocomplete="street-address"/>
            {$_modx->getPlaceholder('fi.error.address') == null ? '' : '<p class="help-block">' ~ $_modx->lexicon("site.form.error.address") ~ '</p>'}
        </div>

        <div class="form-group {$_modx->getPlaceholder('fi.error.zip') == null ? '' : 'has-error'}">
            <label for="zipcode">{$_modx->lexicon('site.form.zipcode')} *</label>
            <input class="form-control" type="text" value="{$_modx->getPlaceholder('fi.zip')}" name="zip" id="zipcode" autocomplete="postal-code"/>
            {$_modx->getPlaceholder('fi.error.zip') == null ? '' : '<p class="help-block">' ~ $_modx->lexicon("site.form.error.zipcode") ~ '</p>'}
        </div>

        <div class="form-group {$_modx->getPlaceholder('fi.error.city') == null ? '' : 'has-error'}">
            <label for="city">{$_modx->lexicon('site.form.city')} *</label>
            <input class="form-control" type="text" value="{$_modx->getPlaceholder('fi.city')}" name="city" id="city" autocomplete="address-level2"/>
            {$_modx->getPlaceholder('fi.error.city') == null ? '' : '<p class="help-block">' ~ $_modx->lexicon("site.form.error.city") ~ '</p>'}
        </div>

        <div class="form-group {$_modx->getPlaceholder('fi.error.message') == null ? '' : 'has-error'}">
            <label for="messagearea">{$_modx->lexicon('site.form.message')} *</label>
            <textarea class="form-control" rows="4" name="message" id="messagearea">{$_modx->getPlaceholder('fi.message')}</textarea>
            {$_modx->getPlaceholder('fi.error.message') == null ? '' : '<p class="help-block">' ~ $_modx->lexicon("site.form.error.message") ~ '</p>'}
        </div>

        <div class="form-group">
            <p class="help-block">* {$_modx->lexicon('site.form.requiredfields')}</p>
        </div>
    
        <input type="submit" class="btn btn-default" name="contact-submit" value="{$_modx->lexicon('site.form.submit')}"/>
              
    </form>
</div>