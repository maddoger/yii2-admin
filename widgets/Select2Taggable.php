<?php

namespace maddoger\admin\widgets;

use yii\base\Exception;
use yii\web\JsExpression;

class Select2Taggable extends Select2
{
	/**
	 * @var \maddoger\core\behaviors\Taggable
	 */
	public $behavior = null;

	public $readOnly = false;

	public $url = null;

	public function init()
	{
		$this->options['class'] = 'form-control';

		if (!$this->behavior) {
			throw new Exception('Behavior must be set');
		}

		$pluginsOptions = [
			'allowClear' => true,
			'minimumInputLength' => 1,
			'maximumInputLength' => 20,
			'multiple' => true,
			'formatResult' => new JsExpression('function(result, container, query, escapeMarkup) {
		var markup=[];
		window.Select2.util.markMatch(result.text, query.term, markup, escapeMarkup);
		var str = markup.join("");
		if (result.isNew) {
			str = \'<span class="label label-success">' . \Yii::t('maddoger/admin', 'New') . '</span> \' + str;
		}
		return str;
	}'),
			'initSelection' => new JsExpression('function (element, callback) {
                                var data = [];
                                $(element.val().split(",")).each(function () {
									data.push({id: this, text: this});
								});
                                callback(data);
                            }'),
			'sortResults' => new JsExpression('function (results, container, query) {
		if (query.term) {
            // use the built in javascript sort function
            return results.sort(function(a, b) {
            	if (a.isNew != b.isNew) {
            		if (a.isNew) {
            			return 1;
            		} else {
            			return -1;
            		}
            	}
                if (a.text.length > b.text.length) {
                    return 1;
                } else if (a.text.length < b.text.length) {
                    return -1;
                } else {
                    return 0;
                }
            });
        }
        return results;
	}'),
		];

		if ($this->value === null) {
			$this->value = $this->behavior->getString();
		}

		if (!$this->readOnly) {

			$pluginsOptions['createSearchChoice'] =
				new JsExpression('function (term, data) {
            if ($(data).filter(function () {
                return this.text.localeCompare(term) === 0;
            }).length === 0) {
                return {id: term, text: term, isNew: true};
            }
        }');
		}

		if ($this->url !== null) {

			$pluginsOptions['ajax'] = [
				'url' => $this->url,
				'dataType' => 'json',
				'data' => new JsExpression('function(term,page) { return {search:term}; }'),
				'results' => new JsExpression('function(data,page) { return {results:data.results}; }'),
			];

		} else {
			$pluginsOptions['data'] = [
				'results' => $this->behavior->getAllExistingTagsFormAutocomplete(),
				'text' => new JsExpression('function(item) { return item.text; }'),
			];
		}

		$this->pluginOptions = array_merge($pluginsOptions, $this->pluginOptions);


		parent::init();
	}
}