import { gsap } from 'gsap';
import { Animation } from "./Animation";

export class YReveal extends Animation {

	constructor(elem, props=null) {
		super(elem, props);

		this.child = elem.children[0]

		this.t = null;
	}

	timeline() {
		if(this.t) this.t.clear();

		if(this.child) {
			this.h = this.child.getBoundingClientRect().height;
			this.t = gsap.timeline();

			this.t.fromTo(this.child, {y: -this.h}, {y: 0, duration: 0.8, ease: 'power2.out'}, 0);
			this.t.fromTo(this.elem, {y: this.h * 1.2}, {y: 0, duration: 0.8, ease: 'power2.out'}, 0);
		}

		return this.t;
	}
}