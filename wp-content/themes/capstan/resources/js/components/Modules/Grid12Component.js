import { StaticComponent } from 'libs';
import { gsap } from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';

export class Grid12Component extends StaticComponent{
	constructor(e, b){
		super(e, b);
		this.query 			= this.query.bind(this);
		this.loadMore 		= this.loadMore.bind(this);
		this.seachByName 	= this.seachByName.bind(this);

		this.search		= this.element.querySelector('.search>input');
		this.grid		= this.element.querySelector('.grid>.content');
		this.more		= this.element.querySelector('.link-plus');
		this.form 		= this.element.querySelector('.filters-forms')

		this.offices = {
			elem: this.element.querySelector('.offices'),
			content: '',
			height: 1,
			opened: true
		}
		this.titles	 = {
			elem: this.element.querySelector('.titles'),
			content: '',
			height: 1,
			opened: true
		}

		this.lastUse = 'null';

		this.filters = document.querySelector('.filters');

		this.offices.changeByList 	= this.changeByList.bind(this, this.offices)
		this.offices.changeBySelect = this.changeBySelect.bind(this, this.offices)
		this.offices.toggle			= this.toggleFilter.bind(this, this.offices)

		this.titles.changeByList 	= this.changeByList.bind(this, this.titles)
		this.titles.changeBySelect 	= this.changeBySelect.bind(this, this.titles)
		this.titles.toggle			= this.toggleFilter.bind(this, this.titles)

		this.offices.value 	= this.offices.elem.querySelector('.value');
		this.titles.value 	= this.titles.elem.querySelector('.value');

		this.offices.select = this.offices.elem.querySelector('select');
		this.titles.select 	= this.titles.elem.querySelector('select');

		this.offices.ul	= this.offices.elem.querySelector('ul');
		this.titles.ul	= this.titles.elem.querySelector('ul');

		this.offices.li	= Array.from(this.offices.ul.querySelectorAll('li'));
		this.titles.li	= Array.from(this.titles.ul.querySelectorAll('li'));

		this.getLawyerIds();
    }

	attach(){
		this.search.addEventListener('keyup', this.seachByName, {useCapture: true});
		this.form.addEventListener('submit', this.seachByName, {useCapture: true})

		this.offices.li.forEach(li => {
			li.addEventListener('click', this.offices.changeByList)
		});
		this.titles.li.forEach(li => {
			li.addEventListener('click', this.titles.changeByList)
		});

		this.offices.elem.addEventListener('click', this.offices.toggle, {useCapture: true});
		this.titles.elem.addEventListener('click', this.titles.toggle, {useCapture: true});

		this.offices.select.addEventListener('change', this.offices.changeBySelect)
		this.titles.select.addEventListener('change', this.titles.changeBySelect)

		this.more.addEventListener('click', this.loadMore);

		this.viewport.on('resize', this.handleResize);
		this.viewport.on('media', this.handleMedia);

		this.toggleFilter(this.offices);
		this.toggleFilter(this.titles);

		window.addEventListener('click', this.focusout);

		this.handleMedia();
		this.search.focus()
	}

	detach(){
		this.search.removeEventListener('keyup', this.seachByName);

		this.offices.li.forEach(li => {
			li.removeEventListener('click', this.titles.changeByList)
		});
		this.titles.li.forEach(li => {
			li.removeEventListener('click', this.titles.changeByList)
		});

		this.offices.elem.removeEventListener('click', this.offices.toggle);
		this.titles.elem.removeEventListener('click', this.titles.toggle);

		this.offices.select.removeEventListener('change', this.offices.changeBySelect)
		this.titles.select.removeEventListener('change', this.titles.changeBySelect)

		this.more.removeEventListener('click', this.loadMore);

		this.viewport.off('resize', this.handleResize);
		this.viewport.off('media', this.handleMedia);

		window.removeEventListener('click', this.focusout);

		if(this.st) this.st.kill();
	}

	handleResize = () => {
		let last = this.offices.ul.style.height;
		this.offices.ul.style.height = '';
		this.offices.height = this.offices.ul.getBoundingClientRect().height - 78;
		this.offices.ul.style.height = last;

		last = this.titles.ul.style.height;
		this.titles.ul.style.height = '';
		this.titles.height = this.titles.ul.getBoundingClientRect().height - 78;
		this.titles.ul.style.height = last;
	}

	handleMedia = () => {
		if(this.st) this.st.kill()

		if(this.viewport.medias.desktop) {
			this.st = ScrollTrigger.create({
				trigger: this.grid,
				start: 'top +=100px',
				end: 'bottom center',
				pin: this.filters,
				pinSpacing: false
			});
		}
 	}

	toggleFilter = (filter, e) => {
		if(!this.viewport.medias.desktop) return

		if(filter.opened) {
			this.closeFilter(filter)
		} else {
			this.closeFilter(this.offices)
			this.closeFilter(this.titles)

			this.openFilter(filter)
			e.preventDefault();
			e.stopPropagation();
		}
	}

	openFilter(filter) {
		if(filter.opened) return;

		filter.ul.style.height = Math.min(Math.min(filter.height, 400), this.viewport.height - 284)+'px';
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
		if(this.offices.opened) this.closeFilter(this.offices)
		if(this.titles.opened) this.closeFilter(this.titles)
	}


	getLawyerIds() {
		this.lawyers	= Array.from(this.grid.querySelectorAll('.item'));
		this.lawyerIds 	= this.lawyers.map(elem => elem.getAttribute('data-id'));
	}

	seachByName(e) {
		e.stopPropagation();
		e.preventDefault();
		this.query(true);
		this.lastUse = 'search';
	}

	changeByList(filter, event) {
		filter.content = filter.select.value = event.srcElement.getAttribute('data-value');
		filter.value.innerHTML = event.srcElement.innerHTML;
		this.query(true);
		this.lastUse = 'list';
	}

	changeBySelect(filter) {
		filter.content = filter.select.value
		filter.value.innerHTML = filter.select.querySelector('option[value="'+filter.content+'"]').innerHTML
		this.query(true);
	}

	loadMore() {
		this.query();
	}

	async query(reset=false) {
		let response = await fetch(ADMIN_AJAX_URL, {
			method: 'POST',
			credentials: 'include',
			headers: {'Content-type': 'application/x-www-form-urlencoded'},
			body: new URLSearchParams({
				action: 'capstan_filter_lawyers',
				search: this.search.value,
				office: this.offices.content,
				status: this.titles.content,
				notin: 	!reset?this.lawyerIds:''
			}).toString()
		});

		const result = await response.json();
		this.update(result, reset);
	}

	update(data, reset=false) {
		if(reset) this.grid.innerHTML = '';

		data.lawyers.forEach(lawyer => {
			let content = `
				<div class="item" data-id="${lawyer.ID}">
					<div class="image">
						<picture>
							<img src="${lawyer.image}" alt="${lawyer.alt}" draggable="false">
						</picture>
						<div class="overlay">
							<div class="border"></div>
							<div class="inner">
								<div class="work-for-container">
									<div class="work-for">Bureau</div>
									<a href="${lawyer.officeUrl}" class="office">${lawyer.officeName}</a>
								</div>
								<div class="icons">`;
									if(lawyer.socials.phone)
										content += `<a href="tel:${lawyer.socials.phone.replaceAll(' ', '')}"><i class="icon-phone"></i></a>`;

									if(lawyer.socials.mail)
										content += `<a href="mailto:${lawyer.socials.mail}"><i class="icon-mail"></i></a>`;

									if(lawyer.socials.linkedin)
										content += `<a href="${lawyer.socials.linkedin.url}" target="${lawyer.socials.linkedin.target}"><i class="icon-in"></i></a>`;
								content += `</div>
								<a href="${lawyer.url}" class="lawyer-cta">VOIR LE PROFIL<i class="icon-right"></i></a>
							</div>
						</div>
					</div>
					<a href="${lawyer.url}">
						<div class="subtitle">${lawyer.title}</div>
						<div class="job">${lawyer.status}</div>
					</a>
				</div>`;

				this.grid.innerHTML += content
		});

		this.getLawyerIds()

		if(this.lawyers.length < parseInt(data.total)) this.more.style.display = 'block';
		else this.more.style.display = 'none';

		this.handleMedia();

		if(this.lastUse == 'search') this.search.focus()
	}
}