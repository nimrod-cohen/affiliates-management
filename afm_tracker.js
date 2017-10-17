(function() {
	if (typeof(window["afm_tracker"]) == "undefined") {
		window.afm_tracker = {

			id: '',
			linkId: -1,
			uId: -1,

			load: function () {
				//assign unique user id for life
				this.id = this.gc("afm.usrid");

				if (this.id == null) {
					this.id = this.genId();
					this.sc("afm.usrid", this.id, afm_server_info.keep_days);
				}

				var ref = document.location.href;

				this.linkId = this.gc("afm.link_id");
				if (this.linkId == null) {
					//utm_content
					if (/(?:sid)=/.test(ref))
					{
						this.linkId = /(?:sid)=(.*?)(?:&|$)/.exec(ref)[1];
						this.sc("afm.link_id", this.linkId, afm_server_info.keep_days);

						if (/(?:uid)=/.test(ref))
						{
							this.uId = /(?:uid)=(.*?)(?:&|$)/.exec(ref)[1];
							this.sc("afm.aff_id",this.uId, afm_server_info.keep_days);
						}
					}
					else //new user, no campaign.
						this.sc("afm.link_id", "-1", afm_server_info.keep_days);
				}
				else //prolonging current owner.
				{
					this.sc("afm.link_id",this.linkId,afm_server_info.keep_days);
					this.uId = this.gc("afm.aff_id");
					this.sc("afm.aff_id",this.uId,afm_server_info.keep_days);
				}

				//utm_source
				if (/(?:utm_source)=/.test(ref)) {
					var src = /(?:utm_source)=(.*?)(?:&|$)/.exec(ref)[1];
					this.sc("afm.source", src, afm_server_info.keep_days);
				}

				//utm_medium
				if (/(?:utm_medium)=/.test(ref)) {
					var medium = /(?:utm_medium)=(.*?)(?:&|$)/.exec(ref)[1];
					this.sc("afm.medium", medium, afm_server_info.keep_days);
				}

				//utm_content
				if (/(?:utm_content)=/.test(ref)) {
					var content = /(?:utm_content)=(.*?)(?:&|$)/.exec(ref)[1];
					this.sc("afm.content", content, afm_server_info.keep_days);
				}

				//utm_campaign
				if (/(?:utm_campaign)=/.test(ref)) {
					var cmp = /(?:utm_campaign)=(.*?)(?:&|$)/.exec(ref)[1];
					this.sc("afm.campaign", cmp, afm_server_info.keep_days);
				}

				//utm_term
				if (/(?:utm_term)=/.test(ref)) {
					var term = /(?:utm_term)=(.*?)(?:&|$)/.exec(ref)[1];
					this.sc("afm.term", term, afm_server_info.keep_days);
				}

			},

			genId: function () {
				var id = [];
				for (var i = 0; i < 24; i++) id.push("0123456789abcdefghijklmnopqrstuvwxyz"[Math.floor(Math.random() * 36)]);
				return id.join("");
			},

			//get cookie value
			gc: function (name) {
				var b = new RegExp("(?:^|; )" + name + "=([^;]*)", "i");
				var c = document.cookie.match(b);
				return (c && c.length == 2) ? c[1] : null
			},

			//set cookie value for X days
			sc: function (name, val, days) {
				var expires = "";
				if (days) {
					var date = new Date();
					date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
					expires = "; expires=" + date.toGMTString();
				}
				document.cookie = name + "=" + val + expires + ";domain=" + this.tld() + "; path=/";
			},

			log: function (event) {
				jQuery.ajax({
					url: afm_server_info.ajax_url,
					type: 'post',
					data: {
						action: 'afm_log',
						security: afm_server_info.nonce,
						event: event,
						link_id: this.linkId,
						tracker_id: this.id,
						aff_id : this.uId,
						utm: this.utm()
					},
					success: function (response) {
						console.log(response);
					}
				});
			},

			//calculate top level domain
			tld : function()
			{
				if((document.domain.match(/\./g)||[]).length < 2)
					return "."+document.domain;
				else if(document.domain.match(/(?:[\w-]+\.){1}(?:com|net|org|co|ac|gov|edu|mil|info|nom|firm|gen|ind|idv|me)?(?:\.[a-z]{2})?$/i))
					return "."+(/(?:[\w-]+\.){1}(?:com|net|org|co|ac|gov|edu|mil|info|nom|firm|gen|ind|idv|me)?(?:\.[a-z]{2})?$/i.exec(document.domain)[0]);
				else if (document.domain.match(/.+?(\.[\w-]+\.[a-z]{2,4})$/i))
					return /.+?(\.[\w-]+\.[a-z]{2,4})$/i.exec(document.domain)[1];
				else
					return document.domain
			},

			utm: function () {
				return {
					source: this.gc("afm.source"),
					medium: this.gc("afm.medium"),
					campaign: this.gc("afm.campaign"),
					term: this.gc("afm.term"),
					content: this.gc("afm.content")
				}
			}
		};
	}

	var t = window.afm_tracker;
	t.load();

	if(!t.gc("afm.session_clicked") && t.gc("afm.link_id") != "-1")
	{
		t.log("click");
		t.sc("afm.session_clicked",true,0);
	}

})();