/* ===========================================================
 * Bootstrap: fileinput.js v3.0.0-p7
 * http://jasny.github.com/bootstrap/javascript.html#fileinput
 * ===========================================================
 * Copyright 2012 Jasny BV, Netherlands.
 *
 * Licensed under the Apache License, Version 2.0 (the "License")
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 * ========================================================== */

+function ($) {
	"use strict"

	var isIE = window.navigator.appName == 'Microsoft Internet Explorer'

	// FILEUPLOAD PUBLIC CLASS DEFINITION
	// =================================

	var Fileupload = function (element, options) {
		this.$element = $(element)

		this.$input = this.$element.find('input[type=file]')
		if (this.$input.length === 0) return

		this.name = this.$input.attr('name') || options.name

		this.browseServerConnectorUrl = options.browseServerConnectorUrl

		this.$hidden = this.$element.find('input[type=hidden][name="' + this.name + '"]')
		if (this.$hidden.length === 0) {
			this.$hidden = $('<input type="hidden" />')
			this.$hidden.attr('name', this.name)
			this.$element.prepend(this.$hidden)
		}

		this.$preview = this.$element.find('.preview.has-file')
		var height = this.$preview.css('height')
		if (this.$preview.css('display') != 'inline' && height != '0px' && height != 'none') this.$preview.css('line-height', height)

		this.original = {
			exists: this.$element.hasClass('has-file'),
			preview: this.$preview.html(),
			hiddenVal: this.$hidden.val()
		}

		if (!this.$element.hasClass('no-file') && !this.$element.hasClass('has-file')) {
			if (this.original.hiddenVal == '') {
				this.$element.addClass('no-file')
			}
		}

		this.listen()
	}

	Fileupload.prototype.listen = function () {
		this.$input.css('text-align', 'center')
		this.$input.on('change.bs.fileinput', $.proxy(this.change, this))
		$(this.$input[0].form).on('reset', $.proxy(this.reset, this))

		this.$element.find('.trigger-button').on('click', $.proxy(this.trigger, this))
		this.$element.find('.clear-button').on('click', $.proxy(this.clear, this))

		this.$element.find('.browse-server-button').on('click', $.proxy(this.browseServer, this))
	},

	Fileupload.prototype.browseServer = function (e) {

		if ($.fn.elfinder === undefined || this.browseServerConnectorUrl===undefined) return

		var parent = this

		var dialog = $('#fileupload-elfinder')
		if (dialog.length == 0) {
			dialog = $('<div id="fileupload-elfinder" />')
			$('body').append(dialog)
			dialog.dialogelfinder({
				url : this.browseServerConnectorUrl,
				autoOpen: false,
				getFileCallback : function(file) {
					parent.setFile(file)
					$(dialog).dialogelfinder('close')
					//window.close()
				}
			})
		}
		$(dialog).dialogelfinder('open')

		return
	},

	Fileupload.prototype.setFile = function (file) {

		this.$hidden.val(file.url)

		if (this.$preview.length > 0 && (typeof file.type !== "undefined" ? file.type.match('image.*') : file.name.match(/\.(gif|png|jpe?g)$/i))) {

			var preview = this.$preview
			var element = this.$element

			var $img = $('<img>').attr('src', file.url)

			element.find('.fileinput-filename').text(file.name)

				// if parent has max-height, using `(max-)height: 100%` on child doesn't take padding and border into account
				if (preview.css('max-height') != 'none') $img.css('max-height', parseInt(preview.css('max-height'), 10) - parseInt(preview.css('padding-top'), 10) - parseInt(preview.css('padding-bottom'), 10) - parseInt(preview.css('border-top'), 10) - parseInt(preview.css('border-bottom'), 10))

				preview.html('<a href="'+file.url+'" target="_blank">'+$img+'</a>')

		} else {
			this.$preview.html('<a href="'+file.url+'" target="_blank">'+file.name+'</a>')
		}

		this.$element.addClass('has-file').removeClass('no-file')

		this.$element.find('.filename').text(file.name)
		this.$element.trigger('change.bs.fileinput')

		this.$element.find('.clear-button').removeClass('disabled')
		this.$element.find('.reset-button').removeClass('disabled')
	},

	Fileupload.prototype.change = function (e) {
		if (e.target.files === undefined) e.target.files = e.target && e.target.value ? [
			{name: e.target.value.replace(/^.+\\/, '')}
		] : []
		if (e.target.files.length === 0) return

		/*this.$hidden.val('')
		 this.$hidden.attr('name', '')
		 this.$input.attr('name', this.name)*/

		var file = e.target.files[0]

		if (this.$preview.length > 0 && (typeof file.type !== "undefined" ? file.type.match('image.*') : file.name.match(/\.(gif|png|jpe?g)$/i)) && typeof FileReader !== "undefined") {
			var reader = new FileReader()
			var preview = this.$preview
			var element = this.$element

			reader.onload = function (re) {
				var $img = $('<img>').attr('src', re.target.result)
				e.target.files[0].result = re.target.result

				element.find('.fileinput-filename').text(file.name)

				// if parent has max-height, using `(max-)height: 100%` on child doesn't take padding and border into account
				if (preview.css('max-height') != 'none') $img.css('max-height', parseInt(preview.css('max-height'), 10) - parseInt(preview.css('padding-top'), 10) - parseInt(preview.css('padding-bottom'), 10) - parseInt(preview.css('border-top'), 10) - parseInt(preview.css('border-bottom'), 10))

				preview.html($img)
			}

			reader.readAsDataURL(file)
		} else {

			this.$preview.text(file.name)
		}

		this.$element.addClass('has-file').removeClass('no-file')

		this.$element.find('.filename').text(file.name)
		this.$element.trigger('change.bs.fileinput')

		this.$element.find('.clear-button').removeClass('disabled')
		this.$element.find('.reset-button').removeClass('disabled')
	},

	Fileupload.prototype.clear = function (e) {
		if (e) e.preventDefault()

		this.$hidden.val('')
		//this.$hidden.attr('name', this.name)
		//this.$input.attr('name', '')

		//ie8+ doesn't support changing the value of input with type=file so clone instead
		if (isIE) {
			var inputClone = this.$input.clone(true)
			this.$input.after(inputClone)
			this.$input.remove()
			this.$input = inputClone
		} else {
			this.$input.val('')
		}

		this.$preview.html('')
		this.$element.find('.filename').text('')
		this.$element.addClass('no-file').removeClass('has-file')

		this.$element.find('.clear-button').addClass('disabled')
		this.$element.find('.reset-button').removeClass('disabled')

		if (e !== false) {
			this.$input.trigger('change')
			this.$element.trigger('clear.bs.fileinput')
		}
	},

	Fileupload.prototype.reset = function () {
		this.clear(false)

		this.$hidden.val(this.original.hiddenVal)
		this.$preview.html(this.original.preview)
		this.$element.find('.filename').text('')

		if (this.original.exists) {
			this.$element.addClass('has-file').removeClass('no-file')
			this.$element.find('.clear-button').removeClass('disabled')
		}
		else {
			this.$element.addClass('no-file').removeClass('has-file')
			this.$element.find('.clear-button').addClass('disabled')
		}

		this.$element.find('.reset-button').addClass('disabled')

		this.$element.trigger('reset.bs.fileinput')
	},

	Fileupload.prototype.trigger = function (e) {
		this.$input.trigger('click')
		e.preventDefault()
	}


	// FILEUPLOAD PLUGIN DEFINITION
	// ===========================

	$.fn.fileinput = function (options) {
		return this.each(function () {
			var $this = $(this)
				, data = $this.data('fileinput')
			if (!data) $this.data('fileinput', (data = new Fileupload(this, options)))
			if (typeof options == 'string') data[options]()
		})
	}

	$.fn.fileinput.Constructor = Fileupload

}(window.jQuery)
