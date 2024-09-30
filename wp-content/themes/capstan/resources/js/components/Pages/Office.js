import { StaticComponent } from 'libs';
import { gsap } from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';

export class Office extends StaticComponent{
	constructor(e, b){
		super(e, b);
		this.query 			= this.query.bind(this);
		this.loadMore 		= this.loadMore.bind(this);

		this.grid		= this.element.querySelector('.lawyers-grid');
		this.more		= this.element.querySelector('.lawyers-plus');

		this.officeId 	= this.grid.getAttribute('data-office');

		this.getLawyerIds();
    }

	attach(){
		if(this.more) this.more.addEventListener('click', this.loadMore);
	}

	detach(){
		if(this.more) this.more.addEventListener('click', this.loadMore);
	}

	getLawyerIds() {
		this.lawyers	= Array.from(this.grid.querySelectorAll('.item'));
		this.lawyerIds 	= this.lawyers.map(elem => elem.getAttribute('data-id'));
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
				search: '',
				office: this.officeId,
				status: '',
				notin: 	this.lawyerIds,
				max: 3
			}).toString()
		});

		const result = await response.json();
		this.update(result);
	}

	update(data) {
		data.lawyers.forEach(lawyer => {
			let content = `
				<div class="item" data-id="${lawyer.ID}">
					<div class="image overflow-h" data-animation="imageReveal">
						<picture>
							<img src="${lawyer.image}" alt="${lawyer.alt}" draggable="false">
						</picture>
					</div>
					<a href="${lawyer.url}">
						<div class="subtitle">${lawyer.title}</div>
						<div class="job">${lawyer.status}</div>
					</a>
				</div>`;

			this.grid.innerHTML += content
		});

		this.getLawyerIds()
		
		if(parseInt(data.total) > this.lawyers.length) this.more.style.display = 'block';
		else this.more.style.display = 'none';
	}
}