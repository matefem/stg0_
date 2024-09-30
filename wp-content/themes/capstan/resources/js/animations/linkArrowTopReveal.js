import { gsap } from 'gsap';
import { Animation } from "./Animation";

export class linkArrowTopReveal extends Animation {

	constructor(elem, props=null) {
		super(elem, props);

		this.text = elem.querySelector('.inner')
		this.icon = elem.querySelector('i')

		this.t = null;
	}

	timeline() {
		if(this.t) this.t.clear();
		this.t = gsap.timeline();

		this.t.fromTo(this.icon, {opacity: 0}, {opacity: 1, duration: 1, ease: 'none'}, 0.2);
		this.t.fromTo(this.text, {y: 50}, {y: 0, duration: 0.6, ease: 'power2.out'}, 0.2);

		return this.t;
	}
}