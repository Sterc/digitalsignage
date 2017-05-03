<?php
/**
 * Properties Swedish lexion entries
 *
 * @package login
 * @subpackage lexicon
 * @author Joakim Nyman <joakim@edgefive.com>
 */
/* ForgotPassword snippet */
$_lang['prop_forgotpassword.emailtpl_desc'] = 'Bekräftelsemeddelandets e-post mall.';
$_lang['prop_forgotpassword.emailtpltype_desc'] = 'Typ av mall som förses till emailTpl egenskapen. Standardvärdet är en Chunk.';
$_lang['prop_forgotpassword.senttpl_desc'] = 'Meddelande mall som visas när ett e-post meddelande har skickats framgångsrikt.';
$_lang['prop_forgotpassword.senttpltype_desc'] = 'Typ av mall som förses till sentTpl egenskapen. Standardvärdet är en Chunk.';
$_lang['prop_forgotpassword.tpl_desc'] = 'Glömt lösenord formulärets mall.';
$_lang['prop_forgotpassword.tpltype_desc'] = 'Typ av mall som förses till tpl egenskapen. Standardvärdet är en Chunk.';
$_lang['prop_forgotpassword.errtpl_desc'] = 'Mall som skall omsluta felmeddelanden.';
$_lang['prop_forgotpassword.errtpltype_desc'] = 'Typ av mall att använda för errTpl.';
$_lang['prop_forgotpassword.emailsubject_desc'] = 'Ämne för glömt lösenord meddelandet.';
$_lang['prop_forgotpassword.emailtplalt_desc'] = '(Valfri) Klartext alternativ till bekräftelsemeddelandets e-post mall.';
$_lang['prop_forgotpassword.resetresourceid_desc'] = 'Resursen att hänvisa användare till i bekräftelsemeddelandet, där ResetPassword snippeten anropas.';

/* Login snippet */
$_lang['prop_login.actionkey_desc'] = 'REQUEST variabeln som indikerar vilken åtgärd som ska vidtas.';
$_lang['prop_login.loginkey_desc'] = 'Åtgärdsnyckel för inloggning.';
$_lang['prop_login.logoutkey_desc'] = 'Åtgärdsnyckel för utloggning.';
$_lang['prop_login.tpltype_desc'] = 'Typ av mallar som förses till in- och utloggningsformulären.';
$_lang['prop_login.logintpl_desc'] = 'Mall för inloggningsformuläret.';
$_lang['prop_login.logouttpl_desc'] = 'Mall för utloggningsformuläret.';
$_lang['prop_login.prehooks_desc'] = 'Skript som skall anropas innan användaren loggas in eller ut. Detta kan vara en komma-separerad lista med hooks, och om den första misslyckas kommer de följande inte att anropas. En hook kan också vara ett namn på den Snippet som skall köras.';
$_lang['prop_login.posthooks_desc'] = 'Skript som skall anropas efter att användaren har loggats in eller ut. Detta kan vara en komma-separerad lista med hooks, och om den första misslyckas kommer de följande inte att anropas. En hook kan också vara ett namn på den Snippet som skall köras.';
$_lang['prop_login.errtpl_desc'] = 'Felmeddelandets mall.';
$_lang['prop_login.errtpltype_desc'] = 'Typ av mall för felmeddelandet.';
$_lang['prop_login.loginresourceid_desc'] = 'Resursen som användare skall hänvisas till efter lyckad inloggning. 0 hänvisar till sig självt.';
$_lang['prop_login.loginresourceparams_desc'] = 'Ett JSON objekt med parametrar att lägga till på inloggningens omdirigerings-URL. Ex: {"test":123}';
$_lang['prop_login.logoutresourceid_desc'] = 'Resurs ID att hänvisa till efter lyckad utloggning. 0 hänvisar till sig självt.';
$_lang['prop_login.logoutresourceparams_desc'] = 'Ett JSON objekt med parametrar att lägga till på utloggningens omdirigerings-URL. Ex: {"test":123}';
$_lang['prop_login.loginmsg_desc'] = 'Valfritt etikett text för inloggningsåtgärden. Om tomt, används lexikonsträngen för Login som standard.';
$_lang['prop_login.logoutmsg_desc'] = 'Valfritt etikett text för utloggningsåtgärden. Om tomt, används lexikonsträngen för Logout som standard.';
$_lang['prop_login.redirecttoprior_desc'] = 'Om sant, omdirigeras användaren till den refererande sidan (HTTP_REFERER) vid lyckad inloggning.';
$_lang['prop_login.redirecttoonfailedauth_desc'] = 'Om satt till ett numeriskt värde annat än 0, omdirigeras användaren till denna sida om deras inloggningsförsök misslyckas.';
$_lang['prop_login.rememberme_desc'] = 'Valfritt. Namnet på det fält som används till "Kom ihåg mig"-kryssrutan för att bevara inloggningsläget. Standardvärdet är `rememberme`.';
$_lang['prop_login.contexts_desc'] = '(Experimentellt) En komma-separerad lista på kontexter att logga in till. Som standard används aktuell kontext om värdet inte är satt.';
$_lang['prop_login.toplaceholder_desc'] = 'Om angivet kommer utskriften från Login snippeten att lagras i en platshållare vid detta namn istället för direktutskrift av det returnerade innehållet.';

/* Profile snippet */
$_lang['prop_profile.prefix_desc'] = 'En sträng att använda som prefix för alla platshållare för fält som blir satta av denna Snippet.';
$_lang['prop_profile.user_desc'] = 'Valfritt. Antingen ett användar-ID eller användarnamn. Om angivet kommer denna användare att användas istället för den just nu inloggade.';
$_lang['prop_profile.useextended_desc'] = 'Om extra fält i formuläret skall lagras i profilens extended fält. Detta kan vara användbart för att lagra ytterligare användarfält.';

/* Register snippet */
$_lang['prop_register.submitvar_desc'] = 'Variabeln att leta efter för att ladda Register funktionaliteten. Om den lämnas tom eller är satt till false, kommer Register att behandla formuläret i alla POST förfrågningar.';
$_lang['prop_register.usergroups_desc'] = 'Valfritt. En komma-separerad lista på användargruppnamn eller IDn att lägga till den ny-registrerade användaren i.';
$_lang['prop_register.usergroupsfield_desc'] = 'Namnet på det fält som skall användas för att ange användargruppen/grupperna som användaren automatiskt skall läggas till i. Används endast om värdet inte är blankt.';
$_lang['prop_register.submittedresourceid_desc'] = 'Om angivet, omdirigeras användaren till den angivna resursen efter att användaren har skickat registreringsformuläret.';
$_lang['prop_register.usernamefield_desc'] = 'Namnet på det fält som skall användas för den nya användarens användarnamn.';
$_lang['prop_register.passwordfield_desc'] = 'Namnet på det fält som skall användas för den nya användarens lösenord.';
$_lang['prop_register.emailfield_desc'] = 'Namnet på det fält som skall användas för den nya användarens e-postadress.';
$_lang['prop_register.successmsg_desc'] = 'Valfritt. Om omdirigering genom submittedResourceId parametern inte används visas detta meddelande i stället.';
$_lang['prop_register.persistparams_desc'] = 'Valfritt. Ett JSON objekt med parametrar att bibehålla under hela registreringsprocessen. Användbart när man använder omdirigering i ConfirmRegister för att omdirigera till en annan sida (t.ex. för kundvagnar).';
$_lang['prop_register.prehooks_desc'] = 'Skript som skall anropas innan formuläret passerar validering. Detta kan vara en komma-separerad lista med hooks, och om den första misslyckas kommer de följande inte att anropas. En hook kan också vara ett namn på den Snippet som skall köras.';
$_lang['prop_register.posthooks_desc'] = 'Skript som skall anropas efter att användaren har registrerats. Detta kan vara en komma-separerad lista med hooks, och om den första misslyckas kommer de följande inte att anropas. En hook kan också vara ett namn på den Snippet som skall köras.';
$_lang['prop_register.useextended_desc'] = 'Om extra fält i formuläret skall lagras i profilens extended fält. Detta kan vara användbart för att lagra ytterligare användarfält.';
$_lang['prop_register.excludeextended_desc'] = 'En komma-separerad lista på fält att exkludera från lagring som extended fält.';
$_lang['prop_register.activation_desc'] = 'Om aktivering skall krävas för fullständig registrering. Om sant kommer användare inte att markeras som aktiva innan de har aktiverat sitt konto. Standardvärdet är true. Fungerar endast om formuläret förmedlar ett e-post fält.';
$_lang['prop_register.activationttl_desc'] = 'Antalet minuter innan registrerings e-posten förfaller. Standard är 3 timmar.';
$_lang['prop_register.activationresourceid_desc'] = 'Resurs ID där ConfirmRegister snippeten för aktivering finns.';
$_lang['prop_register.activationemail_desc'] = 'Om angivet, kommer aktiverings e-post skickas till denna adress istället för den ny-registrerade användarens adress.';
$_lang['prop_register.activationemailsubject_desc'] = 'Ämnet för aktiverings e-posten.';
$_lang['prop_register.activationemailtpltype_desc'] = 'Typ av mallar som förses till aktiverings e-posten.';
$_lang['prop_register.activationemailtpl_desc'] = 'Aktiverings e-postens mall.';
$_lang['prop_register.activationemailtplalt_desc'] = '(Valfritt) Klartext alternativ till aktiverings e-postens mall.';
$_lang['prop_register.moderatedresourceid_desc'] = 'Om en prehook anger användaren som moderated, skicka då användaren till denna resurs istället för submittedResourceId. Lämna blankt för att kringgå.';
$_lang['prop_register.placeholderprefix_desc'] = 'Prefixet att använda till alla platshållare satta av denna snippet.';
$_lang['prop_register.recaptchaheight_desc'] = 'Om `recaptcha` är satt som en preHook anger detta höjden för reCaptcha widgeten.';
$_lang['prop_register.recaptchatheme_desc'] = 'Om `recaptcha` är satt som en preHook anger detta temat för reCaptcha widgeten.';
$_lang['prop_register.recaptchawidth_desc'] = 'Om `recaptcha` är satt som en preHook anger detta bredden för reCaptcha widgeten.';
$_lang['prop_register.mathminrange_desc'] = 'Om `math` är satt som en preHook anger detta minimi-intervallen för varje siffra i ekvationen.';
$_lang['prop_register.mathmaxrange_desc'] = 'Om `math` är satt som en preHook anger detta maximi-intervallen för varje siffra i ekvationen.';
$_lang['prop_register.mathfield_desc'] = 'Om `math` är satt som en preHook anger detta namnet på fältet som används till svaret.';
$_lang['prop_register.mathop1field_desc'] = 'Om `math` är satt som en preHook anger detta namnet på det fält som används till det första talet i ekvationen.';
$_lang['prop_register.mathop2field_desc'] = 'Om `math` är satt som en preHook anger detta namnet på det fält som används till det andra talet i ekvationen.';
$_lang['prop_register.mathoperatorfield_desc'] = 'Om `math` är satt som en preHook anger detta namnet på det fält som används till operatorn i ekvationen.';
$_lang['prop_register.validatepassword_desc'] = 'Om de skickade lösenorden skall valideras vid registrering eller inte. Rekommenderas att lämna detta till Ja om du inte skapar egna lösenord i en hook.';
$_lang['prop_register.generatepassword_desc'] = 'Om satt till Ja, kommer Register att skapa ett slumpat lösenord till användaren vilket skriver över eventuella försedda lösenord. Användbart för automatiskt skapande av lösenord.';
$_lang['prop_register.trimpassword_desc'] = 'Om satt till Ja, kommer Register att putsa bort onödiga blanktecken från det försedda lösenordet.';
$_lang['prop_register.allowedfields_desc'] = 'Om angivet, kommer detta att begränsa fälten som kan lagras i den nya användaren till denna komma-separerade lista. Begränsar även extended fält.';
$_lang['prop_register.removeexpiredregistrations_desc'] = 'Om sant, kommer registrerade användare som har gått ut, har oanvända aktiveringsförfrågningar och aldrig har blivit aktiverade att raderas. Det rekommenderas att låta detta vara på för att förhindra spam.';
$_lang['opt_register.chunk'] = 'Chunk';
$_lang['opt_register.file'] = 'Fil';
$_lang['opt_register.inline'] = 'Infogad';
$_lang['opt_register.embedded'] = 'Inbäddad';
$_lang['opt_register.blackglass'] = 'Svart Glas';
$_lang['opt_register.clean'] = 'Ren';
$_lang['opt_register.red'] = 'Röd';
$_lang['opt_register.white'] = 'Vit';
$_lang['opt_register.asc'] = 'Stigande';
$_lang['opt_register.desc'] = 'Fallande';

/* ConfirmRegister snippet */
$_lang['prop_confirmregister.redirectto_desc'] = 'Valfritt. Efter lyckad bekräftelse, omdirigera till denna resurs.';
$_lang['prop_confirmregister.redirectparams_desc'] = 'Valfritt. Ett JSON objekt med parametrar att förmedla vid omdirigering genom redirectTo.';
$_lang['prop_confirmregister.authenticate_desc'] = 'Autentisera och logga in användaren till den aktuella kontexten efter bekräftad registrering. Standard är true.';
$_lang['prop_confirmregister.authenticatecontexts_desc'] = 'Valfritt. En komma-separerad lista på kontexter att autentisera till. Standard är den aktuella kontexten.';
$_lang['prop_confirmregister.errorpage_desc'] = 'Valfritt. Om angivet, omdirigeras användaren till en anpassad felmeddelandesida om de försöker komma åt denna sida efter att ha aktiverat sitt konto.';

/* ResetPassword snippet */
$_lang['prop_resetpassword.tpl_desc'] = 'Mall för meddelandet nollställ lösenord.';
$_lang['prop_resetpassword.tpltype_desc'] = 'Typ av mall som förses. Standard är en Chunk.';
$_lang['prop_resetpassword.loginresourceid_desc'] = 'Resursen att hänvisa användare till vid lyckad bekräftelse.';

/* UpdateProfile snippet */
$_lang['prop_updateprofile.allowedextendedfields_desc'] = 'Valfritt. Om angivet begränsas fälten som uppdateras i Extended fälten till namnet på fält i denna komma-separerade lista.';
$_lang['prop_updateprofile.allowedfields_desc'] = 'Valfritt. Om angivet begränsas fälten som uppdateras till namnet på fält i denna komma-separerade lista.';
$_lang['prop_updateprofile.emailfield_desc'] = 'Namn på fältet för e-postadressen i formuläret.';
$_lang['prop_updateprofile.excludeextended_desc'] = 'En komma-separerad lista på fält att exkludera från lagring som Extended fält.';
$_lang['prop_updateprofile.placeholderprefix_desc'] = 'Prefix som skall användas för alla platshållare satta av denna snippet.';
$_lang['prop_updateprofile.posthooks_desc'] = 'Skript som skall anropas efter att användaren har registrerats. Detta kan vara en komma-separerad lista med hooks, och om den första misslyckas kommer de följande inte att anropas. En hook kan också vara ett namn på den Snippet som skall köras.';
$_lang['prop_updateprofile.prehooks_desc'] = 'Skript som skall anropas innan formuläret passerar validering. Detta kan vara en komma-separerad lista med hooks, och om den första misslyckas kommer de följande inte att anropas. En hook kan också vara ett namn på den Snippet som skall köras.';
$_lang['prop_updateprofile.redirecttologin_desc'] = 'Om en användare inte är inloggad och försöker komma åt denna resurs, hänvisa dem till sidan Åtkomst nekad.';
$_lang['prop_updateprofile.reloadonsuccess_desc'] = 'Om sant, kommer sidan att hänsiva till sig självt med en GET parameter för att förhindra dubbelförsändelder. Om falskt kommer den bara sätta en success platshållare.';
$_lang['prop_updateprofile.submitvar_desc'] = 'Variabeln att leta efter för att ladda UpdateProfile funktionaliteten. Om tomt eller satt till false kommer UpdateProfile att behandla formuläret vid alla POST förfrågningar.';
$_lang['prop_updateprofile.syncusername_desc'] = 'Om satt till ett kolumnnamn i Profilen, kommer UpdateProfile att försöka synka användarnamnet till detta fält efter lyckad lagring.';
$_lang['prop_updateprofile.useextended_desc'] = 'Om extra fält i formuläret skall lagras i profilens extended fält. Detta kan vara användbart för att lagra ytterligare användarfält.';
$_lang['prop_updateprofile.user_desc'] = 'Valfritt. Om angivet laddas användaren med det angivna IDt eller användarnamnet istället för den aktuella användaren.';

/* ChangePassword snippet */
$_lang['prop_changepassword.submitvar_desc'] = 'Variabeln att leta efter för att ladda ChangePassword funktionaliteten. Om tomt eller satt till false kommer ChangePassword att behandla formuläret vid alla POST förfrågningar.';
$_lang['prop_changepassword.fieldoldpassword_desc'] = 'Namnet på fältet för det gamla lösenordet.';
$_lang['prop_changepassword.fieldnewpassword_desc'] = 'Namnet på fältet för det nya lösenordet.';
$_lang['prop_changepassword.fieldconfirmnewpassword_desc'] = 'Valfritt. Om angivet namnet på fältet för bekräftelse av lösenord kommer det fältet att jämföras emot det nya lösenordet när formuläret skickas.';
$_lang['prop_changepassword.prehooks_desc'] = 'Skript som skall anropas när formuläret passerar validering men före lagring. Detta kan vara en komma-separerad lista med hooks, och om den första misslyckas kommer de följande inte att anropas. En hook kan också vara ett namn på den Snippet som skall köras.';
$_lang['prop_changepassword.posthooks_desc'] = 'Skript som skall anropas när användaren har registrerats. Detta kan vara en komma-separerad lista med hooks, och om den första misslyckas kommer de följande inte att anropas. En hook kan också vara ett namn på den Snippet som skall köras.';
$_lang['prop_changepassword.redirecttologin_desc'] = 'Om en användare inte är inloggad och försöker komma åt denna resurs, omdirigera dem till sidan Åtkomst nekad.';
$_lang['prop_changepassword.reloadonsuccess_desc'] = 'Om sant, kommer sidan att hänvisa till sig självt med en GET parameter för att förhindra dubbelförsändelser. Om falskt kommer den bara sätta en success platshållare.';
$_lang['prop_changepassword.successmessage_desc'] = 'Om reloadOnSuccess är satt till false, skrivs detta meddelande ut i [prefix].successMessage platshållaren.';
$_lang['prop_changepassword.placeholderprefix_desc'] = 'Prefix som skall användas till alla platshållare satta av denna snippet.';

/* isLoggedIn snippet */
$_lang['prop_isloggedin.contexts_desc'] = 'En komma-separerad lista med kontexter för vilka autentiseringsstatus skall kontrolleras. Om inget är angivet, används aktuell kontext som standard.';
$_lang['prop_isloggedin.redirectto_desc'] = 'ID för den resurs användaren skall omdirigeras till om användaren inte är inloggad. Som standard används unauthorized_page.';
$_lang['prop_isloggedin.redirectparams_desc'] = 'Om redirectTo används, ett JSON objekt med REQUEST parametrar att skicka med vid omdirigering.';

/* ActiveUsers snippet */
$_lang['prop_activeusers.tpl'] = 'Den Chunk som skall användas vid utskrift av varje aktiv användare.';
$_lang['prop_activeusers.tplType'] = 'Typ av mall som förses. Standard är en Chunk.';
$_lang['prop_activeusers.sortBy'] = 'Fält att sortera användare enligt.';
$_lang['prop_activeusers.sortDir'] = 'Riktning att sortera användare enligt.';
$_lang['prop_activeusers.limit'] = 'Antal användare att begränsa visningen till.';
$_lang['prop_activeusers.offset'] = 'Start index för det begränsade antalet användare som skall visas.';
$_lang['prop_activeusers.classKey'] = 'Klassnyckeln att använda när användare hämtas. Standard är modUser. Du kan sätta detta till ett klass namn som utökar modUser, om du vill.';
$_lang['prop_activeusers.placeholderprefix_desc'] = 'Prefix som skall användas till alla platshållare satta av denna snippet.';
$_lang['prop_activeusers.toplaceholder_desc'] = 'Om angivet sätts utskriften för snippeten till en platshållare vid detta namn istället för att returnera innehållet direkt.';