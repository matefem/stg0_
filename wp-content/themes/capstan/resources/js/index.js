import 'core-js/stable';
import 'regenerator-runtime/runtime';

import gsap from 'gsap';
import { ScrollTrigger } from "gsap/ScrollTrigger";
import { ScrollToPlugin } from "gsap/ScrollToPlugin";
import { SplitText } from "gsap/SplitText";

import * as Components from './components';
import * as Animations from './animations';

import { CapstanBootstrap } from './bootstrap/CapstanBootstrap';
import { AnimatorClassModule } from './modules';

import {
   Env,
   AjaxPageModule
} from 'libs';


//Gsap default params
gsap.registerPlugin(ScrollTrigger);
gsap.registerPlugin(ScrollToPlugin);
gsap.registerPlugin(SplitText);
gsap.config({force3D: true});
gsap.defaults({ease: "none", duration: 0.5});

const bootstrap = new CapstanBootstrap();
      bootstrap.components.register(Components);

const animatorClassModule = bootstrap.get(AnimatorClassModule);
      animatorClassModule.register(Animations);


bootstrap.viewport.add({
    addClass:false,
    name:'mobile'
});
bootstrap.viewport.add({
    name:'medium',
    minWidth:769
});
bootstrap.viewport.add({
    name:'desktop',
    minWidth:960
});
bootstrap.viewport.add({
    name: 'large',
    minWidth:1280
});


const ajaxPageModule    = bootstrap.get(AjaxPageModule);
const imageLoaderModule = bootstrap.imageLoader;

ajaxPageModule.loader.callback = async response => {
    try {
        const title = decodeURI(response.headers.get('X-Meta-Title'));
        if (title) {
            const titleDom = document.querySelector('title');
            if (titleDom) titleDom.innerHTML = title;
        }
    }
    catch (e) {
        console.error(e);
    }
};

(async function(){
    await bootstrap.run();
    document.body.style.opacity = 1;
})()

export default bootstrap;