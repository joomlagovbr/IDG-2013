/**@license boxplus strings for localization
 * @author  Levente Hunyadi
 * @version 0.9.3
 * @remarks Copyright (C) 2010-2011 Levente Hunyadi
 * @remarks Licensed under GNU/GPLv3, see http://www.gnu.org/licenses/gpl-3.0.html
 * @see     http://hunyadi.info.hu/projects/boxplus
 **/

/*
* Requires MooTools 1.2 or later.
* Annotated for use with Google Closure Compiler's advanced optimization
* method when supplemented with a MooTools extern file.
*/

;
(function () {
	/** Language strings. */
	var localizations = {
		'en': {
			language: 'English',
			first: 'First',
			prev: 'Previous',
			next: 'Next',
			last: 'Last',
			start: 'Start slideshow',
			stop: 'Stop slideshow',
			close: 'Close',
			enlarge: 'Enlarge',
			shrink: 'Shrink',
			download: 'Download',
			metadata: 'Image metadata',
			notFound: 'Document not found or not available for download.',
			unknownType: 'The embedded content to be displayed in this window is of type <code>%s</code>, which is not currently supported by your browser.\nInstalling the appropriate browser plug-in usually solves the problem.'
		},
		'de': {
			language: 'Deutsch',
			first: 'Erstes',
			prev: 'Zurück',
			next: 'Weiter',
			last: 'Letztes',
			start: 'Diashow starten',
			stop: 'Diashow anhalten',
			close: 'Schließen',
			enlarge: 'Vergrößern',
			shrink: 'Verkleinern',
			download: 'Download',
			metadata: 'Bild Metadaten'
		},
		'es': {
			language: 'Español',
			first: 'Primera',
			prev: 'Anterior',
			next: 'Siguiente',
			last: 'Última',
			start: '',
			stop: '',
			close: 'Cerrar',
			enlarge: 'Ampliar',
			shrink: 'Reducir',
			download: 'Descargar',
			metadata: 'Metadatos de la imagen'
		},
		'fr': {
			language: 'Français',
			first: 'Première',
			prev: 'Précédente',
			next: 'Suivante',
			last: 'Dernière',
			start: 'Démarrer diaporama',
			stop: 'Arrêter diaporama',
			close: 'Fermer',
			enlarge: 'Agrandir',
			shrink: 'Réduire',
			download: 'Télécharger',
			metadata: 'Métadonnées images'
		},
		'bg': {
			language: 'български език',
			first: 'Първа',
			prev: 'Предишна',
			next: 'Следваща',
			last: 'Последна',
			start: 'Старт на слайдшоу',
			stop: 'Спиране на слайдшоу',
			close: 'Затвори',
			enlarge: 'Увеличи',
			shrink: 'Намали',
			download: 'Изтегляне',
			metadata: 'Метаданни на снимката'
		},
		'he': {
			language: 'עִבְרִית',
			first: 'ראשון',
			prev: 'קודם‎',
			next: 'הבא‎',
			last: 'אחרון‎',
			start: 'להתחיל מצגת שקפים',
			stop: 'לסיים מצגת שקפים',
			close: 'קרוב',
			enlarge: 'להגדיל‎',
			shrink: 'להקטין',
			download: 'להוריד',
			metadata: 'מטא-נתונים'
		},
		'hu': {
			language: 'Magyar',
			first: 'Első',
			prev: 'Előző',
			next: 'Következő',
			last: 'Utolsó',
			start: 'Diavetítés indítása',
			stop: 'Diavetítés megállítása',
			close: 'Bezár',
			enlarge: 'Nagyítás',
			shrink: 'Kicsinyítés',
			download: 'Letöltés',
			metadata: 'Kép metadatai'
		},
		'nl': {
			language: 'Nederlands',
			first: 'Eerste',
			prev: 'Vorige',
			next: 'Volgende',
			last: 'Laatste',
			start: 'Start presentatie',
			stop: 'Stop presentatie',
			close: 'Sluiten',
			enlarge: 'Vergroten',
			shrink: 'Verkleinen',
			download: 'Download',
			metadata: 'Metadata van de afbeelding'
		},
		'pl': {
			language: 'Polski',
			first: 'Pierwszy',
			prev: 'Poprzedni',
			next: 'Następny',
			last: 'Ostatni',
			start: 'Uruchom pokaz slajdów',
			stop: 'Zatrzymaj pokaz slajdów',
			close: 'Zamknij',
			enlarge: 'Powiększ',
			shrink: 'Pomniejsz',
			download: 'Pobierz',
			metadata: 'Metadane obrazka'
		},
		'pt': {
			language: 'Português',
			first: 'Primeiro',
			prev: 'Anterior',
			next: 'Próximo',
			last: 'Último',
			start: 'Iniciar slideshow',
			stop: 'Parar slideshow',
			close: 'Fechar',
			enlarge: 'Ampliar',
			shrink: 'Reduzir',
			download: 'Download',
			metadata: 'Metadados de imagem'
		},
		'ru': {
			language: 'Русский язык',
			first: 'Первый',
			prev: 'Предыдущий',
			next: 'Следующий',
			last: 'Последний',
			start: 'Запустить слайд-шоу',
			stop: 'Остановить слайд-шоу',
			close: 'Закрыть',
			enlarge: 'Увеличить',
			shrink: 'Сократить',
			download: 'Загрузить',
			metadata: 'Метаданные изображений'
		},
		'sk': {
			language: 'Slovenčina',
			first: 'Prvá',
			prev: 'Vzad',
			next: 'Vpred',
			last: 'Posledná',
			start: 'Spustiť prezentáciu',
			stop: 'Zastaviť prezentáciu',
			close: 'Zavrieť',
			enlarge: 'Rozšíriť',
			shrink: 'Zúžiť',
			download: 'Stiahnutie',
			metadata: 'Metaúdaje obrázkov'
		}
	};

	/**
	* Get language strings and/or set language and country for localization.
	* @param langcode A language code in the ISO format "en".
	* @param countrycode A country code in the ISO format "US".
	* @return Language strings as an object.
	*/
	function apply(langcode, countrycode) {
		var isocode = countrycode ? langcode + '-' + countrycode : langcode;

		// get language strings for selected language
		var code = localizations[isocode] ? isocode : ( localizations[langcode] ? langcode : '' );

		var localization = {
			language: '',
			first: '',
			prev: '',
			next: '',
			last: '',
			start: '',
			stop: '',
			close: '',
			enlarge: '',
			shrink: '',
			download: '',
			metadata: '',
			notFound: '',
			unknownType: ''
		};
		if (code) {
			Object.append(localization, localizations[code])
		}

		// maps possibly minified property names to their expanded (true) name
		var mapping = {
			language: 'language',
			first: 'first',
			prev: 'prev',
			next: 'next',
			last: 'last',
			start: 'start',
			stop: 'stop',
			close: 'close',
			enlarge: 'enlarge',
			shrink: 'shrink',
			download: 'download',
			metadata: 'metadata',
			notFound: 'not-found',
			unknownType: 'unknown-type'
		};

		// apply language strings
		Object.each(localization, function (value, key) {
			var name = mapping[key];
			$$('div.boxplus-' + name).set('title', value);
			$$('a.boxplus-' + name).set('html', value);
			$$('span.boxplus-' + name).set('html', value);
		});
	};

	// automatically select language when DOM tree is ready loading
	window.addEvent('domready', function () {
		var pattern = /\blang=([a-z]{2,})(?:-([A-Z]{2,}))?\b/;
		$$('script[src*="boxplus"][src*=lang]').each(function (item) {
			var match = pattern.exec(item.get('src'));
			if (match) {
				apply(match[1], match[2]);
			}
		});
	});
})();
