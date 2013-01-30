<!-- BEGIN: main -->
<div id="users">
	<h2 class="line padding_0" style="margin-bottom:5px">{LANG.login}</h2>
	<form id="loginForm" action="{USER_LOGIN}" method="post" class="register1 clearfix">
		<fieldset>
			<div class="info padding_0" style="padding-top:10px;padding-bottom:10px;">
				{LANG.login_info}
			</div>
			<div class="clearfix rows">
				<label> {LANG.account} </label>
				<input type="text" id="login_iavim" name="nv_login" value="{DATA.nv_login}" class="required" maxlength="{NICK_MAXLENGTH}" />
			</div>
			<div class="clearfix rows">
				<label> {LANG.password} </label>
				<input type="password" id="password_iavim" name="nv_password" value="{DATA.nv_password}" class="required password" maxlength="{PASS_MAXLENGTH}" />
			</div>
			<!-- BEGIN: captcha -->
			<div class="clearfix rows">
				<label> {LANG.captcha} </label>
				<img id="vimglogin" alt="{N_CAPTCHA}" src="{SRC_CAPTCHA}" width="{GFX_WIDTH}" height="{GFX_HEIGHT}" /><img src="{CAPTCHA_REFR_SRC}" class="refesh" alt="{CAPTCHA_REFRESH}" onclick="nv_change_captcha('vimglogin','seccode_iavim');"/>
			</div>
			<div class="clearfix rows">
				<label> {LANG.retype_captcha} </label>
				<input type="text" name="nv_seccode" id="seccode_iavim" class="required" maxlength="{GFX_MAXLENGTH}" />
			</div>
			<!-- END: captcha -->
			<input name="nv_redirect" value="{DATA.nv_redirect}" type="hidden" /><input type="submit" value="{LANG.login_submit}" class="submit" />
		</fieldset>
		<div class="rows"  style="margin-top:20px;">
			<ul>
				<li>
					<a title="{LANG.register}" href="{USER_REGISTER}">{LANG.register}</a>
				</li>
				<li>
					<span>|</span>
					<a title="{LANG.lostpass}" href="{USER_LOSTPASS}">{LANG.lostpass}</a>
				</li>
			</ul>
		</div>
		<!-- BEGIN: openid -->
		<div style="margin:20px;">
			<img style="margin-left:10px;vertical-align:middle;" alt="{LANG.openid_login}" src="{OPENID_IMG_SRC}" width="{OPENID_IMG_WIDTH}" height="{OPENID_IMG_HEIGHT}" />
			<div style="margin-bottom:10px;">
				{DATA.openid_info}
			</div>
			<!-- BEGIN: server -->
			<a href="{OPENID.href}" title="{OPENID.title}"><img style="margin-left: 10px;margin-right:2px;vertical-align:middle;" alt="{OPENID.title}" src="{OPENID.img_src}" width="{OPENID.img_width}" height="{OPENID.img_height}" />{OPENID.title}</a>
			<!-- END: server -->
		</div>
		<!-- END: openid -->
	</form>
</div>
<!-- END: main -->