import { StaticComponent } from 'libs';
import { debounce } from "debounce";
import { gsap } from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';
import { FollowLinkModule } from '../../modules/FollowLinkModule';

export class Articles extends StaticComponent{
	constructor(e, b){
		super(e, b);

		// this.randomArticles = this.element.querySelector(".random-articles > div:first-child");
		this.buttonLoadMore = this.element.querySelector('.button-load-more');

		this.filters = this.element.querySelector(".filters");
		this.filtersForm = this.element.querySelector(".filters-forms");
		this.inputSearch = this.filtersForm.querySelector("input[name='input']");
		this.filterTypes = [...this.element.querySelectorAll(".filter-types li")];
		this.filterThemes = this.filtersForm.querySelector(".themes.list");

		this.columns = this.element.querySelector(".columns");
		this.topWrapper = this.columns.querySelector(":scope > .right > .top");
		// this.randomWrapper = this.element.querySelector(".random-articles");
		this.resultArticles = this.element.querySelector(".result-articles");
		this.buttonFavoris = this.element.querySelector(".button-favoris");

		this.query = debounce(this.query.bind(this), 300);
		this.onClickType = this.onClickType.bind(this);
		this.onClickButtonMore = this.onClickButtonMore.bind(this);
		this.onClickFavoris = this.onClickFavoris.bind(this);
		this.onKeyUpSearch = this.onKeyUpSearch.bind(this);

		this.themes = {elem: this.filterThemes, content: '', height: 1, opened: true}

		this.themes.changeByList = this.changeByList.bind(this, this.themes)
		this.themes.changeBySelect = this.changeBySelect.bind(this, this.themes)
		this.themes.toggle = this.toggleFilter.bind(this, this.themes);
		this.themes.value = this.themes.elem.querySelector('.value');
		this.themes.select = this.themes.elem.querySelector('select');
		this.themes.ul = this.themes.elem.querySelector('ul');
		this.themes.li = Array.from(this.themes.ul.querySelectorAll('li'));

		this.getPostsIds();
    }

	attach(){
		if (this.buttonLoadMore) this.buttonLoadMore.addEventListener('click', this.onClickButtonMore);
		if (this.buttonFavoris) this.buttonFavoris.addEventListener("click", this.onClickFavoris);
		this.filterTypes.forEach(el => el.addEventListener("click", this.onClickType));
		this.inputSearch.addEventListener("keyup", this.onKeyUpSearch);
		this.filtersForm.addEventListener("submit", this.onSubmitForm);

		this.themes.li.forEach(li => li.addEventListener('click', this.themes.changeByList));
		this.themes.elem.addEventListener('click', this.themes.toggle, {useCapture: true});
		this.themes.select.addEventListener('change', this.themes.changeBySelect);
		this.toggleFilter(this.themes);
		window.addEventListener('click', this.focusout);

		this.viewport.on('resize', this.handleResize);
		this.viewport.on('media', this.handleMedia);

		this.handleMedia();
		this.inputSearch.focus();

		const selectedType = this.filterTypes.find(el => el.classList.contains("selected") && el.dataset.id);
		const selectedTheme = this.themes.li.find(el => el.classList.contains("selected"));
		const typedText = !!this.inputSearch.value;
		if (typedText || selectedType || selectedTheme) {
			if (selectedTheme) {
				selectedTheme.click();
				this.inputSearch.focus();
			}
			else this.inputSearch.dispatchEvent(new KeyboardEvent('keyup', {}));
		}
	}

	detach(){
		if (this.buttonLoadMore) this.buttonLoadMore.removeEventListener('click', this.onClickButtonMore);
		if (this.buttonFavoris) this.buttonFavoris.removeEventListener("click", this.onClickFavoris);
		this.filterTypes.forEach(el => el.removeEventListener("click", this.onClickType));
		this.inputSearch.removeEventListener("keyup", this.onKeyUpSearch);
		this.filtersForm.removeEventListener("submit", this.onSubmitForm);

		this.viewport.off('resize', this.handleResize);
		this.viewport.off('media', this.handleMedia);

		this.themes.li.forEach(li => li.removeEventListener('click', this.themes.changeByList));
		this.themes.elem.removeEventListener('click', this.themes.toggle, {useCapture: true});
		this.themes.select.removeEventListener('change', this.themes.changeBySelect);
		window.removeEventListener('click', this.focusout);
	}

	onSubmitForm = (e) => {
		e.preventDefault();
	}

	handleResize = () => {
		let last = this.themes.ul.style.height;
		this.themes.ul.style.height = '';
		this.themes.height = this.themes.ul.getBoundingClientRect().height - 78;
		this.themes.ul.style.height = last;
	}

	handleMedia = () => {
		if(this.st) this.st.kill()

		if(this.viewport.medias.desktop) {
			this.st = ScrollTrigger.create({
				trigger: this.columns,
				start: 'top +=200px',
				end: 'bottom center',
				pin: this.filters,
				pinSpacing: false
			});
		}
 	}

	openFilter(filter) {
		if(filter.opened) return;

		filter.ul.style.height = Math.min(filter.height, 400)+'px';
		filter.elem.classList.add('active')
		filter.opened = true;
	}
	closeFilter(filter) {
		if(!filter.opened) return;

		filter.ul.style.height = '0px';
		filter.elem.classList.remove('active')
		gsap.to(filter.ul, {scrollTo: 0, duration: 0.5, ease:'power2.out'});
		filter.opened = false;
	}

	focusout = (e) => {
		if(this.themes.opened) this.closeFilter(this.themes)
	}

	changeByList(filter, event) {
		filter.content = filter.select.value = event.srcElement.getAttribute('data-value');
		filter.value.innerHTML = event.srcElement.innerHTML;
		this.query(true);
		this.lastUse = 'list';
	}

	changeBySelect(filter) {
		filter.content = filter.select.value
		filter.value.innerHTML = filter.select.options[filter.select.selectedIndex].innerHTML;
		this.query(true);
	}

	toggleFilter(filter, e) {
		if(filter.opened) {
			this.closeFilter(filter)
		}
		else {
			this.closeFilter(this.themes)

			this.openFilter(filter)
			e.preventDefault();
			e.stopPropagation();
		}
	}

	open() {
		if (this.buttonFavoris) {
			if (document.location.search && document.location.search.includes("favoris=1")) {
				this.buttonFavoris.click();
			}
		}
	}

	getPostsIds() {
		const articles = Array.from(this.element.querySelectorAll('.article-card'));
		this.articlesIds = articles.map(elem => elem.getAttribute('data-id'));
	}

	onClickButtonMore() {this.query(false);}

	onClickFavoris() {
		this.buttonFavoris.classList.toggle("black");
		this.query(true);
	}

	onClickType(e) {
		this.filterTypes.forEach(el => el.classList.remove("selected"));
		e.currentTarget.classList.add("selected");
		this.query(true);
	}

	onKeyUpSearch() {this.query(true);}

	async query(reset = true) {

		const params = {
			action: 'capstan_filter_articles',
			search: this.inputSearch.value,
			theme: this.themes.content,
			type: this.filterTypes.find(el => el.classList.contains("selected")).dataset.id || '',
			notin: this.articlesIds,
			favoris: this.buttonFavoris.classList.contains("black"),
			isLoadMore: !reset
		};

		let listingMod = params.search != '' || params.type || params.favoris || params.theme ? true : false;
		if (params.search == '' && !params.type && !params.favoris && !params.theme) listingMod = false;

		const urlParams = '?terme='+params.search+'&type='+params.type+'&theme='+params.theme;
		const fullUrl = document.location.origin + document.location.pathname + urlParams;
		window.history.pushState({path:fullUrl},'', fullUrl);


		let response = await fetch(ADMIN_AJAX_URL, {
			method: 'POST',
			credentials: 'include',
			headers: {'Content-type': 'application/x-www-form-urlencoded'},
			body: new URLSearchParams(params).toString()
		});

		const result = await response.text();
		this.update(result, listingMod, reset, response.status)
	}

	update(data, listingMod, reset, status = 200) {
		if (!data) {
			data = '<div class="no-result">Aucun résultat ne correspond aux filtres de recherche.</div>';
		}

		if (listingMod) {
			this.topWrapper.classList.remove("visible");
			// this.randomWrapper.style.display = "none";
		}
		else {
			this.topWrapper.classList.add("visible");
			// if (this.viewport.medias.desktop || this.viewport.medias.tablet) {
			// 	this.randomWrapper.style.display = "block";
			// }
			// else {
			// 	this.randomWrapper.style.display = "none";
			// }
		}

		if (!reset) this.resultArticles.insertAdjacentHTML('beforeend', data);
		else this.resultArticles.innerHTML = data;

		this.getPostsIds()

		if (status == 206) {
			this.buttonLoadMore.style.display = "block";
		}
		else this.buttonLoadMore.style.display = "none";

		this.bootstrap.get(FollowLinkModule).after();
		if (reset) gsap.to(window, {scrollTo: 0, duration: 0.5, ease:'power2.out'});
	}
}