<!-- Start artikel groot -->
[[+image:notempty=`
    <tr>
        <td align="center">
            [[+link:neq=``:then=`
                <a href="[[+link]]" target="_blank"><img src="[[++siteurl]][[+image]]" alt="[[+title]]" border="0" style="margin:0; display:block;" class="w600"/></a>
                `:else=`
                <img src="[[++siteurl]][[+image]]" alt="[[+title]]" border="0" style="margin:0; display:block;" class="w600"/>
            `]]
        </td>
    </tr>
`]]
<tr><td height="10"></td></tr>
<tr>
    <td align="center" style="padding:20px;" class="pad10">
        <table cellpadding="0" cellspacing="0" border="0" width="100%">
        [[+title:notempty=`
            <tr>
                <td align="left" style="font-family:Arial, Helvetica, sans-serif; font-size:23px; line-height:30px; color:#333333; font-weight:normal;">[[+title]]</td>
            </tr>
        `]]
        <tr><td height="15"></td></tr>
        [[+text:notempty=`
            <tr>
                <td align="left" style="font-family:Arial, Helvetica, sans-serif; font-size:13px; line-height:19px; color:#333333; text-align:left;">[[+text]]</td>
            </tr>
        `]]
        <tr><td height="15"></td></tr>
        [[+linktext:notempty=`
            [[+link:notempty=`
                <tr>
                    <td align="left" style="font-family:Arial, Helvetica, sans-serif; font-size:13px; line-height:19px; color:#333333; text-align:left;">
                        <table cellpadding="0" cellspacing="0" border="0">
                            <tbody>
                            <tr>
                                <td bgcolor="[[+colorselect]]" align="center" style="font-family:Arial, Helvetica, sans-serif; color:#FFFFFF; font-size:13px;padding:8px 20px;"><a href="[[+link]]" target="_blank" style="color:#FFFFFF; text-decoration:none;">[[+linktext]]</a></td>
                            </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
            `]]
        `]]
        <tr><td height="15"></td></tr>
        </table>
    </td>
</tr>
<!-- Eind artikel groot -->