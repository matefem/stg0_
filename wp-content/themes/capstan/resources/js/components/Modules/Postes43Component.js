import { StaticComponent } from 'libs';
import { gsap } from 'gsap';


import WireframeGlobe from '../../libs/WireframeGlobe.js';

export class Postes43Component extends StaticComponent{
	constructor(e, b){
		super(e, b);


		this.globalFade = this.element.querySelector('.global-fade')
		this.content 	= this.element.querySelector('.content')

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
			elem.open = this.openSelect.bind(this, elem)
			elem.close = this.closeSelect.bind(this, elem)
			elem.change = this.changeSelect.bind(this, elem)
			elem.optns = Array.from(elem.select.querySelectorAll('option'));
		})
    }

	attach(){
		this.selects.forEach(select => {
			select.select.addEventListener('change', select.change)
			select.preview.addEventListener('click', select.open)

			const fake = document.createElement('div');
			fake.classList.add('fake');

			select.optns.forEach(optn => {
				const o = document.createElement('span')
				
				o.classList.add('entry');
				o.innerHTML = optn.innerHTML
				const event = this.setValue.bind(this, select, optn.value ? optn.value : optn.innerHTML, optn.innerHTML);
				o.addEventListener('click', event);

				select.fakeClick.push(event)
				select.fakeOptns.push(o)
				fake.appendChild(o);
			});

			select.fake = fake;
			select.elem.appendChild(fake);
		})
	}

	detach(){
		this.selects.forEach(select => {
			select.select.removeEventListener('change', select.change)
			select.preview.removeEventListener('click', select.open)
			select.fakeOptns.forEach((o, i) => {
				o.removeEventListener('click', select.fakeClick[i])
			})
		})
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

	setValue(elem, value, display = null) {
		elem.select.value = elem.value = value;
		elem.preview.innerHTML = display ? display : value
		elem.preview.classList.remove('empty');
		elem.close()
		this.submit();
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
		let response = await fetch(ADMIN_AJAX_URL, {
			method: 'POST',
			credentials: 'include',
			headers: {'Content-type': 'application/x-www-form-urlencoded'},
			body: new URLSearchParams({
				action: 'capstan_filter_careers',
				city: this.selects[0].value,
				poste: this.selects[1].value
			}).toString()
		});

		const result = await response.json();
		this.update(result);
	}

	update = (result) => {
		let content = `
			<tr>
				<th>Postes</th>
				<th>Type de contrat</th>
				<th>Bureau</th>
			</tr>`;

		result.forEach(entry => {
			content +=`
			<tr class="entry">
				<td class="title">
					<a href="${entry.link}">${entry.title}</a>
					<div class="date">Publié il y a ${entry.time} jours</div>
				</td>
				<td class="info">
					<span class="label">CONTRAT</span>
					<span class="value">${entry.contract}</span>
				</td>
				<td class="info last">
					<span class="label">BUREAU</span>
					<span class="value">${entry.city}</span>
				</td>
			</tr>`;
		});

		this.content.innerHTML = content;

	}
}