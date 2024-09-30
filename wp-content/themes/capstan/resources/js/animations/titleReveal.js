import { gsap } from 'gsap';
import { SplitText } from "gsap/SplitText";
import { Animation } from "./Animation";

export class titleReveal extends Animation {

	constructor(elem, props=null) {
		super(elem, props);

		this.childs	= Array.from(this.elem.querySelectorAll('p'));
		if(this.childs.length == 0) this.childs = [{elem: this.elem, initial: this.elem.innerHTML}]
		else this.childs = this.childs.map(elem => {
			return {
				elem,
				initial: elem.innerHTML
			}
		})
		this.t = null;
		this.buildAtResize = true
	}

	timeline() {
		if(this.t) this.t.clear();
		this.t = gsap.timeline();

		this.reset();

		this.childs.forEach((child, i) => {
			const emContents = [];
			const iContents = [];

			child.elem.querySelectorAll('i').forEach((i, ii) => {
				iContents.push(i.innerHTML)
				i.innerHTML = '***i'+ii+'***'
			});
			child.elem.querySelectorAll('em').forEach((i, ii) => {
				emContents.push(i.innerHTML)
				i.innerHTML = '***em'+ii+'***'
			});

			iContents.forEach((content, ii) => {
				child.elem.innerHTML = child.elem.innerHTML.replace('***i'+ii+'***', content.split(' ').join('</i> <i>'))
			});
			emContents.forEach((content, ii) => {
				child.elem.innerHTML = child.elem.innerHTML.replace('***em'+ii+'***', content.split(' ').join('</em> <em>'))
			});

			const split = new SplitText(child.elem, {type:'lines'});
			const lines = split.lines.map(line => {
				line.classList.add('line-hi')
				return new SplitText(line, {type:'words', wordsClass:"t-word"});
			});

			this.t.fromTo(child.elem, {opacity: 0}, {opacity: 1, duration: 0.3}, 0.);

			lines.forEach((line, i) => {
				this.t.set(line.words, {y: -120, rotation: -12}, 0)
				this.t.to(line.words, {y: 0, rotation: 0, ease:'power2.out', duration:0.6, stagger: 0.04}, i * 0.1 + 0.1)
			});
		})

		return this.t;
	}

	reset() {
		this.childs.forEach(child => {
			child.elem.innerHTML = child.initial;
		})
	}
	
}