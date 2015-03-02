<div id="chat" class="animated fadeIn">
	<div id="actions">
		<input type="text" on-keypress="checkEnter:{{ replyMessage }}" placeholder="Scrie un raspuns..." value="{{ replyMessage }}" />
		<button on-click="reply:{{ replyMessage }}" {{ replyMessage.trim().length < 1 ? 'disabled' : '' }}>Trimite</button>
	</div>
	<div id="replies">
		{{ # replies:index }}
			<div class="reply {{ user == me.id ? 'self' : '' }} animated {{ index < replies_limit ? 'fadeInDown' : 'fadeOutDown' }}">
				<div class="avatar" data-tooltip="{{ friends[ user ] }}" style="background-image: url(/assets/images/avatars/{{ user }}.jpg);" >{{ friends[ user ].name }}</div>
				<div class="message">{{ message }}</div>
			</div>
		{{ / }}
	</div>
</div>