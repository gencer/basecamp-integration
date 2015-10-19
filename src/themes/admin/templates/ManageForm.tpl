<{if $_authLink}>
<{if $_alreadyAuthorised && !$_authTokenSuccess && !$_newAppSaved}>
	<p><{$_Language->Get('basecamp_already_authorised_txt')}></p>
<{/if}>
<br />
<div id='auth_txt'>
<{$_Language->Get('basecamp_authorise_txt')}>: <b> <a href="#" id='bc_authlnk'><{$_Language->Get('basecamp_click_here_lnk')}>.</a> </b>
</div>
<div id='auth_wait' style="display:none">
	<b><{$_Language->Get('basecamp_please_wait_auth')}></b>
</div>
<br />
<br />
<hr />
<{/if }>

<{$_formTxt}>

<tr class="tablerow1_tr">
	<td width="50%" valign="top" align="left" class="tablerow1">
		<span class="tabletitle"><{$_Language->Get('bc_app_redirect_url')}></span>
	</td>
	<td width="50%" valign="top" align="left" class="tablerow1">
		<span class="tabletitle"><{$_redirectUrl}></span>
	</td>
</tr>
<br />
<br />

<{if $_newAppSaved }>
<div id="bc_integrate_now" title="" style='margin:5px'>
	<div><{$_Language->Get('basecamp_js_new_app_saved')}></div>
	<div style="position: absolute; bottom: 12px; left: 10px;"></div>
	<div style="WIDTH: 100%; BORDER: none; COLOR: #ddd6c7; BACKGROUND-COLOR: #ddd6c7; BORDER-TOP: 1px SOLID #FFFFFF;HEIGHT: 1px; MARGIN: 3px 1px 3px 1px; PADDING: 0px; position: absolute; bottom: 32px; margin-bottom: 12px; "></div>
	<div style="position: absolute; bottom: 12px; right: 10px;">
		<input type="button" onfocus="blur();" value="<{$_Language->Get('yes')}>" onclick="$('#bc_integrate_now').dialog('destroy').remove();SWIFT.Basecamp.AdminObject.OpenAuthWindow('<{$_authLink}>');" class="rebuttonblue">
		<input type="button" onfocus="blur();" value="<{$_Language->Get('cancel')}>"  onclick="$('#bc_integrate_now').dialog('destroy').remove();" class="rebuttonred" >
	</div>
</div>
<{/if}>

<script  language ="javascript" type="text/javascript">
	SWIFT.Basecamp.AdminObject.CheckHash();
	$('#bc_authlnk').click(function() {
		SWIFT.Basecamp.AdminObject.OpenAuthWindow('<{$_authLink}>');
	});
	<{if $_newAppSaved }>
		SWIFT.Basecamp.AdminObject.IntegrateNowDialog("<{$_Language->Get('success')}>");
	<{/if}>
</script>