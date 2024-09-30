import { Module, LoaderModule, ViewportModule} from 'libs';
import { TransitionModule } from './TransitionModule';
import { gsap } from 'gsap';


class CapstanLoaderModule extends Module{
    constructor(bootstrap) {
        super(bootstrap);
        this.dependencies.add(LoaderModule);
        this.dependencies.add(TransitionModule);
        this.dependencies.add(ViewportModule);

        this.viewport = bootstrap.viewport;

        this.loader = document.getElementById('loader');
        this.darkOverlay = this.loader.querySelector('.overlay-dark');
        this.redOverlay = this.loader.querySelector('.overlay-red');
        this.first = true;
    }

    showInterLoader = () => {
        this.showingInterloader = true;
        this.loader.style.display = 'flex';
        this.loader.style.opacity = '1';

        const t = gsap.timeline();

        t.fromTo(this.darkOverlay, {x: -this.bootstrap.viewport.width * 1.5, skewX: 20, scaleX: 1},{x:0, skewX: 20, scale: 2, ease:'power2.in', duration: 0.5},0.5);
        t.fromTo(this.redOverlay, {x: -this.bootstrap.viewport.width * 1.5, skewX: 20, scaleX: 1},{x:0, skewX: 20, scale: 2, ease:'power2.in', duration: 0.8},0);
        return t;
    }
    hideInterLoader = () => {
        this.emit('changed')
        this.showingInterloader = false;
        const t = gsap.timeline();

        t.to(this.darkOverlay, {x: this.bootstrap.viewport.width * 1.5, skewX: 20, scaleX: 1, ease:'power2.out', duration: 0.6},0);
        t.to(this.redOverlay, {x: this.bootstrap.viewport.width * 1.5, skewX: 20, scaleX: 1, ease:'power2.out', duration: 0},0);

        t.add(()=>{
            this.loader.style.display = 'none';
        });
        return t;
    }

    async before() {
        let loader = this.bootstrap.get(LoaderModule).loader;

        loader.on('progress', (p) => {});}

    async after() {

        if(!this.first) return;
        this.first = false;
        let timeline = this.bootstrap.get(TransitionModule).timeline;
        let t = gsap.timeline({paued: true});
        t.fromTo(this.loader, {opacity: 1}, {opacity: 0, ease: 'none', duration:0.3}, 0);
        t.add(()=>{
            this.loader.style.display = 'none';

        });
        timeline.shiftChildren(t.totalDuration(), true);
        timeline.add(t,0);

        this.emit('changed')

    }

}

export default CapstanLoaderModule;
export {CapstanLoaderModule};