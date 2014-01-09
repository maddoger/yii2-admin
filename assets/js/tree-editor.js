//Функции для работы с деревьями из списков

//Разворачивает дерево
function treeToggle(el) {
	//Таблица для добавления
	if ($(el)[0].tagName == 'LI') {
		var li = $(el);
	} else {
		var li = $(el).parents('li').first();
	}
	li.find('> ul').slideToggle('fast');
	li.find('> a').first().toggleClass('open');
	return false;
}

function treeUp(el) {
	var li = $(el).parents('li').first();
	var all = li.parent().find('> li');
	if (all.length <= 1)
		return false;
	var li_i = all.index(li);
	if (li_i == 0) {
		all.eq(all.length - 1).after(li);
	} else {
		all.eq(li_i - 1).before(li);
	}
	return false;
}

function treeDown(el) {
	var li = $(el).parents('li').first();
	var all = li.parent().find('> li');
	if (all.length <= 1)
		return false;
	var li_i = all.index(li);
	if (li_i == (all.length - 1)) {
		all.eq(0).before(li);
	} else {
		all.eq(li_i + 1).after(li);
	}

	return false;
}

function treeAdd(level, a) {

	var parentItem, childrenTree;
	if (level <= 1) {
		childrenTree = $(a).closest('form').find('ul.tree-editor:first');
		//Элемент для копирования
		parentItem = childrenTree.find('> li:first').first();

	} else {
		//Элемент для копирования
		parentItem = $(a).closest('li');
		childrenTree = parentItem.find('> ul.tree-editor');
	}


	var clone = parentItem.clone();
	clone.find('ul.tree-editor').empty();

	//Для каждого input поля
	clone.find('input').each(
		function () {
			var input = $(this);
			input.removeClass('hasDatepick');

			if (input.attr('name') == 'level[]') {
				input.val(
					(level <= 1) ? 1 : parseInt(input.val())+1
				);
			} else
			if (input.attr('name') == 'delete[]') {
				input.val(0);
			}
			else {
				//Если задан аттрибут значения по-умолчанию, то применяем его, либо пустое значение
				input.val(input.data('default') || '');
			}
		}
	);

	//Для каждого select поля
	clone.find('select').each(
		function () {
			var select = $(this);
			select.val(select.data('default') || '');
		}
	);

	if (level > 1) {
		$(a).parent().find('> a:first').removeClass('open');
	}
	childrenTree.append(clone).slideDown('fast');
	$('html').scrollTop(clone.offset().top);

	docReady();

	return false;
}
function treeDelete(a) {
	var el = $(a).parent();
	if (el.hasClass('deleted')) {
		el.fadeTo('fast', 1).removeClass('deleted');
		el.find('input[name="delete[]"]').val(0);
	} else {
		el.addClass('deleted').fadeTo('fast', 0.3);
		el.find('input[name="delete[]"]').val(1);
	}
	return false;
}
function treeRemove(a) {
	var message = $(a).data('confirm') || 'Вы уверены?';
	if (confirm(message)) {
		var el = $(a).parent().remove();
	}
	return false;
}