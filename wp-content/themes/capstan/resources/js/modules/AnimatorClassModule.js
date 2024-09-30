import { gsap } from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';
import { Module, ViewportModule } from "libs";


class AnimatorClassModule extends Module{
    constructor(bootstrap) {
        super(bootstrap);

        this.dependencies.add(ViewportModule);
        this.onResize = this.onResize.bind(this);

        this.targets = []
        this.animations = null;
        this.stagger = 0.08;
    }

    register(animations) {
    	this.animations = animations;
    }

    async after() {
        return undefined;
    }

    async before() {
        return undefined;
    }

    onResize(){
    	this.build(false);
    }

    build(force = true) {
        for(let i = 0;i < this.targets.length;i++) {
            this.targets[i].top = this.targets[i].elem.getBoundingClientRect().top;

            if(i && Math.abs(this.targets[i].top - this.targets[i-1].top) < 10) {
                this.targets[i].same = this.targets[i - 1].same + 1
            }
        }

    	this.targets.forEach(target => {
    		if(!target.active) return;
    		if(target.played) {
    			target.anim.reset();
    			return;
    		}

            if(force || target.buildAtResize) {
        		if(target.tl) target.tl.kill();
        		target.tl = gsap.timeline({
        			scrollTrigger: {
        				trigger: target.elem,
        				once: true,
        				toggleActions: 'play complete none none'
        			}
        		})
        		target.tl.add(target.anim.timeline(), target.same * this.stagger);
        		target.tl.add(() => {
        			target.played = true;
        		})
            }
    	})
    }

    handleRouterChange = () => {
        this.targets.forEach(target => {
            target.anim.reset();
            target.tl.kill();
        });

        this.fillTagets();
        this.build();
    }

    fillTagets() {
        this.targets = Array.from(document.querySelectorAll('[data-animation]')).map(elem => {
            const props = {};
            elem.getAttribute('data-props')?.split(';').map(str => str.split(':')).forEach(p => {
                props[p[0]] = p[1]
            });

            return {
                elem,
                anim: elem.getAttribute('data-animation'),
                props,
                tl: null,
                player: false,
                active: false,
                delay: 0,
                top: 0,
                same: 0
            }
        });

        this.targets.forEach(target => {
            if(!this.animations[target.anim]) return;
            target.active = true;

            target.anim = new this.animations[target.anim](target.elem, target.props);
        });
    }

    async run() {
        if(!this.isAttached){
            this.bootstrap.viewport.on('resize', this.onResize);
            this.bootstrap.loader.on('changed', this.handleRouterChange)
            this.isAttached = true;
        }
    }
}

export { AnimatorClassModule };