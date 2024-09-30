import { StaticComponent } from 'libs';
import { gsap } from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';


export class Formation29Component extends StaticComponent{
	constructor(e, b){
		super(e, b);

		this.form 		= 	this.element.querySelector('.inscription-form');
		this.confirm 	= 	this.element.querySelector('.confirm')

		this.subscribe 	= 	this.element.querySelector('.subscribe')

		this.mail 		= this.element.querySelector('.input-mail')
		this.firstname 	= this.element.querySelector('.input-firstname')
		this.lastname 	= this.element.querySelector('.input-lastname')
		this.business 	= this.element.querySelector('.input-business')
		this.role 		= this.element.querySelector('.input-role')

		this.addCalendar = this.element.querySelector('.add-formation-to-calendar')
    }

	attach(){
		this.form.addEventListener('submit', this.handleSubmit, {useCapture: true});
		this.subscribe.addEventListener('click', this.handleSubscribe);

		// console.log(this.addCalendar.getAttribute('data-info'))
		this.addCalendar.href='data:text/ics;base64,' + btoa(this.addCalendar.getAttribute('data-info'))
		// this.update({success: true});
	}

	detach(){
		this.form.removeEventListener('submit', this.handleSubmit, {useCapture: true});
		this.subscribe.removeEventListener('click', this.handleSubscribe);
	}

	handleSubmit = async (e) => {
		e.preventDefault();
		e.stopPropagation();

		let response = await fetch(ADMIN_AJAX_URL, {
			method: 'POST',
			credentials: 'include',
			headers: {'Content-type': 'application/x-www-form-urlencoded'},
			body: new URLSearchParams({
				action: 	'capstan_request_formation',
				mail: 		this.mail.value,
				firstname: 	this.firstname.value,
				lastname: 	this.lastname.value,
				business: 	this.business.value,
				role: 		this.role.value,
				mailto: 	this.form.getAttribute('data-mailto'),
				id: 		this.form.getAttribute('data-id')
			}).toString()
		});

		const result = await response.json();
		this.update(result);
	}

	update(result) {
		if(result.success) {
			const t = gsap.timeline()

			t.fromTo(this.form, {opacity: 1}, {opacity: 0, ease: 'none', duration: 0.5});
			t.set(this.form, {display: 'none'});
			t.set(this.confirm, {display: 'block'});
			t.fromTo(this.confirm, {opacity: 0}, {opacity: 1, ease: 'none', duration: 0.5});

		}
	}

	handleSubscribe = () => {
		gsap.to(window, {scrollTo: {y: this.form, offsetY: 200}, ease: 'power2.inOut', duration: 0.5});
	}
}