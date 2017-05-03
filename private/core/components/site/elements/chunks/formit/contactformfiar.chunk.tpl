<!doctype html>
<html>
<head>
  <title>[[*longtitle:default=`[[++site_name]]`]]</title>
  <style>
    html,body {
      padding: 0;
      background: white;
    }
  </style>
</head>
<body>

<table cellpadding="5" cellspacing="0" border="0" width="600">
    <tr>
      <td colspan="2">
        <p>
            [[%site.form.intro? &topic=`default` &namespace=`site` &name=`[[+name]]`]]
        </p>
      </td>
    </tr>
    <tr><td width="150" valign="top"><strong>{$_modx->lexicon('site.form.name')}:</strong></td><td>[[+name]]</td></tr>
    <tr><td valign="top"><strong>{$_modx->lexicon('site.form.fname')}:</strong></td><td>[[+fname]]</td></tr>
    <tr><td valign="top"><strong>{$_modx->lexicon('site.form.lname')}:</strong></td><td>[[+lname]]</td></tr>
    <tr><td valign="top"><strong>{$_modx->lexicon('site.form.email')}:</strong></td><td>[[+email]]</td></tr>
    <tr><td valign="top"><strong>{$_modx->lexicon('site.form.phone')}:</strong></td><td>[[+phone]]</td></tr>
    <tr><td valign="top"><strong>{$_modx->lexicon('site.form.address')}:</strong></td><td>[[+address]]</td></tr>
    <tr><td valign="top"><strong>{$_modx->lexicon('site.form.zipcode')}:</strong></td><td>[[+zip]]</td></tr>
    <tr><td valign="top"><strong>{$_modx->lexicon('site.form.city')}:</strong></td><td>[[+city]]</td></tr>
    <tr><td valign="top"><strong>{$_modx->lexicon('site.form.message')}:</strong></td><td>[[+message:nl2br]]</td></tr>
</table>

</body>
</html>
