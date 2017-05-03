<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta content="text/html; charset=UTF-8" http-equiv="Content-Type">
    <meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0" name="viewport">
    <title>{$_modx->config.site_name}</title>

    <style type="text/css">
        /* Email Client BUG Fixes */
            .ReadMsgBody, .ExternalClass { width:100%; background-color: #f5f5f5; }
            .ExternalClass, .ExternalClass p, .ExternalClass span, .ExternalClass font, .ExternalClass td, .ExternalClass div { line-height:100%; }
            table { border-spacing:0; border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt; }
            table td { border-collapse:collapse; }
            img { border: 0; }
            html, body { width: 100%; height: 100%; }
            body { margin:0; padding:0; }
        /* End Email Client BUG Fixes */

        /* Embedded CSS link color */
            a         { color:#00a0de; }
            a:link    { color:#00a0de; }
            a:visited { color:#00a0de; }
            a:focus   { color:#00a0de !important; }
            a[href^="tel"], a[href^="sms"] { text-decoration:none; color:#333333; pointer-events:none; cursor:default; }
            .mobile_link a[href^="tel"], .mobile_link a[href^="sms"] { text-decoration:default; color:#FFFFFF !important; pointer-events:auto; cursor:default; }

        /* iPad Text Smoother */
        div, p, a, li, td { -webkit-text-size-adjust:none; }
        @media only screen and (max-width: 599px)
            {
                body { width:auto !important; }
                table[class="w280"], img[class="w280"] { width:280px !important; height:auto !important; }
                td[class="h25"]{ height:20px !important; width:auto !important; display:block !important; }
                td[class="hide"]{ display:none !important; }
                td[class="half"]{ width:100% !important; height:auto !important; display:block !important; }
                td[class="halfC"]{ width:100% !important; height:auto !important; display:block !important; text-align:center !important; }
                td[class="pad10"]{ padding:10px !important; }
                td[class="fontA"]{ font-size:14px !important; height:auto !important; display:block !important; text-align:center !important; padding-top:10px !important; }
            }
    </style>
</head>

<body style="width: 100% ! important; min-width: 239px ! important; font-family:Arial, Helvetica, sans-serif; font-size: 13px; line-height: 140%; margin: 0px; padding: 0px; background-color:#55778b;">
<center>
<table width="100%" border="0" align="center" cellspacing="0" cellpadding="0" bgcolor="#f5f5f5">
    <tbody>
        <tr>
            <td width="100%" valign="top" bgcolor="#d8d8d8" align="center" style="background-color:#f5f5f5;">
            <table cellpadding="0" cellspacing="0" border="0" width="600" class="w600" bgcolor="#FFFFFF">
                <tr>
                    <td align="center" bgcolor="#ec008c" style="font-family:Arial, Helvetica, sans-serif; font-size:13px; color:#FFFFFF; line-height:20px; text-align:center; padding:15px;">
                    Wordt de nieuwsbrief niet correct weergegeven? <a href="%%webversion%%" target="_blank" style="color:#FFFFFF; text-decoration:underline;">Bekijk hier de webversie</a>.
                    </td>
                </tr>
                <tr>
                    <td align="left">
                        <table cellpadding="0" cellspacing="0" border="0">
                            <tr>
                                <td align="left">
                                    <a href="{$_modx->resource.siteUrl}" target="_blank"><img src="" alt="" border="0" width="280" class="w280"/></a>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
                
               {$_modx->runSnippet('getImageList', [
                        'tvname' => 'nbArticleLarge',
                        'tpl'    => 'nbArticleLargeTpl',
                        'limit'  => 10
                    ]
                )}

                {$_modx->runSnippet('getImageList', [
                        'tvname' => 'nbArticleSmall',
                        'tpl'    => 'nbArticleSmallTpl',
                        'limit'  => 10
                    ]
                )}

                <tr>
                    <td align="center">
                        <table cellpadding="0" cellspacing="0" border="0">
                        <tbody>
                            <tr><td height="15"></td></tr>
                        </tbody>
                        </table>
                    </td>
                </tr>

                <tr>
                    <td align="center" bgcolor="#ec008c" style="padding:15px; color: #ffffff; font-family:Arial, Helvetica, sans-serif;" class="pad10">
                    <table cellpadding="0" cellspacing="0" border="0" width="100%">
                    <tr>
                        <td align="center" style="font-family:Arial, Helvetica, sans-serif; font-size:13px; color: #ffffff !important; font-color: #ffffff; line-height:13px; text-align:center;">
                            &copy; {$.php.date('Y')} {$_modx->config.site_name} Klik <a href="%%unsubscribelink%%" title="Uitschrijven" style="color: #ffffff; text-decoration: underline;">hier</a> om je uit te schrijven
                        </td>
                    </tr>
                    </table>
                    </td>
                </tr>
            </table>
            </td>
       </tr>
    </tbody>
</table>
</body>
</html>
