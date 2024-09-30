import { StaticComponent } from 'libs';
import { gsap } from 'gsap';


import WireframeGlobe from '../../libs/WireframeGlobe.js';

export class Contact39Component extends StaticComponent{
	constructor(e, b){
		super(e, b);

		this.globalFade = this.element.querySelector('.global-fade')

		this.form = this.element.querySelector('.contact-form');
		this.visibleForm = this.element.querySelector('.form');

		this.subjectSelect = this.element.querySelector('.message-subject-select');
		this.subjectOptions = Array.from(this.subjectSelect.querySelectorAll('option'));

		this.selects = Array.from(this.element.querySelectorAll('.select-openable')).map((elem, i) => {
			return {
				elem,
				select: elem.querySelector('select'),
				preview: elem.querySelector('.preview'),
				value: '',
				open: null,
				close: null,
				change: null,
				setValue: null,
				optns: [],
				fake: null,
				fakeOptns: [],
				fakeClick: []
			}
		});

		this.selects.forEach(elem => {
			elem.open 	= this.openSelect.bind(this, elem)
			elem.close 	= this.closeSelect.bind(this, elem)
			elem.change = this.changeSelect.bind(this, elem)
			elem.optns 	= Array.from(elem.select.querySelectorAll('option'));
		})

		this.subjectValue 	= '';
		this.subjectSelect = document.querySelector('.message-subject-select');
		this.subjectsDom = Array.from(this.element.querySelectorAll('.message-subject li')).map((li, i) => {
			let active = false;
			const value = li.querySelector('.value').innerHTML

			if(li.classList.contains('active')) {
				active = true;
				this.subjectValue = value;
			}

			return {
				elem: li,
				event: null,
				option: this.subjectOptions[i],
				value,
				active,
			}
		});

		this.companyName 	= document.querySelector('.message-name');
		this.city 			= document.querySelector('.message-city');
		this.content 		= document.querySelector('.message-content');
		this.mail 			= document.querySelector('.message-mail');
		this.phone 			= document.querySelector('.message-phone');
		this.author 		= document.querySelector('.message-author');
		this.poste 			= document.querySelector('.message-poste');

		this.feedback 		= document.querySelector('.feedback');

		this.subjectsDom.forEach(e => {e.event = this.setSubject.bind(this, e)})

		this.restart = document.querySelector('.restart')
    }

	attach(){
		this.form.addEventListener('submit', this.submit, {useCapture: true});

		this.subjectSelect.addEventListener('change', this.subjectSelectChange)

		this.subjectsDom.forEach(li => {
			li.elem.addEventListener('click', li.event);
		})

		this.selects.forEach(select => {
			select.captureClick = document.createElement('span')
			select.captureClick.classList.add('capture-click')
			select.elem.appendChild(select.captureClick)

			select.select.addEventListener('change', select.change)
			select.captureClick.addEventListener('click', select.open)

			const fake = document.createElement('div');
			fake.classList.add('fake');

			select.optns.forEach(optn => {
				const o = document.createElement('span')
				
				o.classList.add('entry');
				o.innerHTML = optn.innerHTML

				const event = this.setValue.bind(this, select, optn.innerHTML);
				o.addEventListener('click', event);

				select.fakeClick.push(event)
				select.fakeOptns.push(o)
				fake.appendChild(o);
			});

			select.fake = fake;
			select.elem.appendChild(fake);
		})

		this.restart.addEventListener('click', this.resetForm, {useCapture: true});
	}

	detach(){
		this.form.removeEventListener('submit', this.submit, {useCapture: true});

		this.subjectSelect.removeEventListener('change', this.subjectSelectChange)

		this.subjectsDom.forEach(li => {
			li.elem.removeEventListener('click', li.event);
		})

		this.selects.forEach(select => {
			select.select.removeEventListener('change', select.change)
			select.preview.removeEventListener('click', select.open)
			select.fakeOptns.forEach((o, i) => {
				o.removeEventListener('click', select.fakeClick[i])
			})
		})

		this.restart.removeEventListener('click', this.resetForm, {useCapture: true});
	}

	setSubject(elem){
		this.subjectsDom.forEach(li => {
			if(li.active) {
				li.active = false;
				li.elem.classList.remove('active')
			}
		})

		this.subjectSelect.value = elem.value;

		elem.elem.classList.add('active')
		elem.active = true
		this.subjectSelect.value = this.subjectValue = elem.value;
	}

	subjectSelectChange = (e) => {
		this.subjectValue = this.subjectSelect.value;
		let elem = null;
		this.setSubject(this.subjectsDom.filter(elem => elem.value == this.subjectValue)[0])
	}

	openSelect(elem) {
		this.openGlobalFade();
		this.globalFade.addEventListener('click', elem.close)

		const t = gsap.timeline();
		t.set(elem.fake, {display: 'block'});
		t.fromTo(elem.fakeOptns, {opacity: 0}, {opacity: 1, stagger: 0.08, ease: 'none'});

		return t;
	}
	closeSelect(elem) {
		this.closeGlobalFade()
		this.globalFade.removeEventListener('click', elem.close)

		const t = gsap.timeline();
		t.to(elem.fakeOptns, {opacity: 0, stagger: {each: 0.05, from: 'end', axis: 'y'}, ease: 'none'});
		t.set(elem.fake, {display: 'none'});

		return t;
	}
	changeSelect(elem) {
		this.setValue(elem, elem.select.value);
	}

	setValue(elem, value) {
		elem.select.value = elem.preview.innerHTML = elem.value = value;
		elem.close()
	}

	openGlobalFade = () => {
		const t = gsap.timeline();

		t.set(this.globalFade, {display: 'block'})
		t.fromTo(this.globalFade, {opacity: 0}, {opacity: 0.86, duration: 0.5, ease: 'none'})

		return t;
	}
	closeGlobalFade = () => {
		const t = gsap.timeline();

		t.to(this.globalFade, {opacity: 0, duration: 0.3, ease: 'none'})
		t.set(this.globalFade, {display: 'none', duration: 0.5, ease: 'none'});

		return t;
	}

	submit = async (e) => {
		e.stopPropagation();
		e.preventDefault();

		let response = await fetch(ADMIN_AJAX_URL, {
			method: 'POST',
			credentials: 'include',
			headers: {'Content-type': 'application/x-www-form-urlencoded'},
			body: new URLSearchParams({
				action: 'capstan_send_message',
				subject: this.subjectValue,
				companyname: this.companyName.value,
				city: this.selects[0].value != '' ? this.selects[0].value : 'Paris',
				content: this.content.value,
				mail: this.mail.value,
				phone: this.phone.value,
				formule: this.selects[1].value,
				author: this.author.value,
				poste: this.poste.value,
			}).toString()
		});

		const result = await response.json();
		this.update(result);
	}

	update = (result) => {
		if(result.success) {
			const t = gsap.timeline();

			t.fromTo(this.visibleForm, {opacity: 1}, {opacity: 0, duration: 0.5, ease:'none'});
			t.set(this.visibleForm, {display: 'none'})
			t.to(window, {scrollTo: 0, duration: 0.5, ease:'power2.out'});
			t.set(this.feedback, {display: 'block'})
			t.fromTo(this.feedback, {opacity: 0}, {opacity: 1, duration: 0.5, ease: 'none'})
		}
	}

	resetForm = (e) => {
		e.preventDefault()

		const t = gsap.timeline();

		this.subjectValue 		= ''
		this.companyName.value 	= ''
		this.city.value 		= ''
		this.content.value 		= ''
		this.mail.value 		= ''
		this.phone.value 		= ''
		this.author.value 		= ''
		this.poste.value 		= ''

		t.fromTo(this.feedback, {opacity: 1}, {opacity: 0, duration: 0.5, ease:'none'});
		t.set(this.feedback, {display: 'none'})
		t.to(window, {scrollTo: 0, duration: 0.5, ease:'power2.out'});
		t.set(this.visibleForm, {display: 'block'})
		t.fromTo(this.visibleForm, {opacity: 0}, {opacity: 1, duration: 0.5, ease: 'none'})
	}
}