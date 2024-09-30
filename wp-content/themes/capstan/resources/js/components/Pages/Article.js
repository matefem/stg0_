import { StaticComponent } from 'libs';
import { gsap } from 'gsap';

export class Article extends StaticComponent{
	constructor(e, b){
		super(e, b);

		this.buttonFavoris = [...this.element.querySelectorAll(".button-favoris-small, .button-favoris")];
		this.buttonScrollTop = this.element.querySelector(".button-scrolltop");

		this.readingBarLine = this.element.querySelector(".post-readingbar .progress");

		this.onClickFavoris = this.onClickFavoris.bind(this);
    }

	attach(){
		if (this.buttonFavoris.length) this.buttonFavoris.forEach(el => el.addEventListener("click", this.onClickFavoris));
		if (this.buttonScrollTop) this.buttonScrollTop.addEventListener("click", this.onClickButtonScrollTop);
	}

	open() {
		if (this.readingBarLine) {
			setTimeout(() => {
				this.st = gsap.fromTo(this.readingBarLine, {scaleX: 0}, {scaleX: 1, ease:'linear', scrollTrigger: {
					trigger: document.body,
					start: 'top top',
					end: 'bottom bottom',
					scrub: 0.8,
					invalidateOnRefresh: true
				}});
			}, 400)
		}

		// Only for pageview
		const params = {
			action: 'capstan_pageview_article',
			postid: this.element.dataset.id
		};
		fetch(ADMIN_AJAX_URL, {
			method: 'POST',
			credentials: 'include',
			headers: {'Content-type': 'application/x-www-form-urlencoded'},
			body: new URLSearchParams(params).toString()
		});
	}

	detach(){
		if (this.buttonFavoris.length) this.buttonFavoris.forEach(el => el.removeEventListener("click", this.onClickFavoris));
		if (this.buttonScrollTop) this.buttonScrollTop.removeEventListener("click", this.onClickButtonScrollTop);

		if (this.st) {this.st.kill();}
	}

	onClickButtonScrollTop() {
		gsap.to(window, {duration: 1, ease: 'power2.inOut', scrollTo: {y: 0}});
	}

	async onClickFavoris() {
		const params = {
			action: 'capstan_toggle_favorite',
			postid: this.element.dataset.id
		};

		let response = await fetch(ADMIN_AJAX_URL, {
			method: 'POST',
			credentials: 'include',
			headers: {'Content-type': 'application/x-www-form-urlencoded'},
			body: new URLSearchParams(params).toString()
		});

		const result = await response.json();

		if (result["success"] && result["result"] == 200) {
			this.buttonFavoris.forEach(el => el.classList.toggle("black"));
		}
	}
}