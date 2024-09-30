export class Animation {
	constructor(elem, props=null) {
		this.elem = elem;
		this.props = props;

		this.t = null;
	}

	timeline() {
		if(this.t) this.t.clear();
		return gsap.timeline();
	}

	reset() {

	}
	
}