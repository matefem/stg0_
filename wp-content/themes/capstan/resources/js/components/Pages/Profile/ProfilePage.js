import {DefaultComponent, Env} from 'libs';
import gsap from 'gsap/gsap-core';
import tools from '../../../tools/Tools';

export class ProfilePage extends DefaultComponent {
    constructor(e, b){
        super(e, b);

        this.sectionProfile = document.querySelector("section#profile");

        this.triggerNavigations = this.element.querySelectorAll(".trigger-profile-navigation");
        this.contents = this.sectionProfile.querySelectorAll(".profile-content[name]");

        this.goToPage = this.goToPage.bind(this);

        this.isForm = false;

        if (this.element.nodeName.toLowerCase() == "form") {
            this.isForm = true;
            this._formData = new FormData(this.element);

            this.button = this.element.querySelector("[name='form-button']");
            this.requiredFields = this.element.querySelectorAll(".form-item.required");
            this.onSubmit = this.onSubmit.bind(this);
        }
    }

    attach() {
        this.triggerNavigations.forEach(el => el.addEventListener("click", this.onTriggerNavigation));

        if (this.isForm) {
            if (this.requiredFields) this.requiredFields.forEach(el => this.bootstrap.components.get(el).on("change", this.onFormItemChange));
            this.element.addEventListener("submit", this.onSubmit);
        }
    }

    detach() {
        this.triggerNavigations.forEach(el => el.addEventListener("click", this.onTriggerNavigation));

        if (this.isForm) {
            this.requiredFields.forEach(el => this.bootstrap.components.get(el).off("change", this.onFormItemChange));
            this.element.removeEventListener("submit", this.onSubmit);
        }
    }

    get formData () {
        if (this.element.nodeName.toLowerCase() == "form") {
            this._formData = new FormData(this.element);
            return this._formData;
        }
        return undefined;
    }

    onFormItemChange = () => {
        let valid = tools.formIsValid(this.requiredFields, this.formData);
        if (this.button) this.button.dataset.disabled = valid ? 0 : 1;
    }

    async onSubmit (e) {e.preventDefault();}

    onTriggerNavigation = (e) => {
        this.goToPage(e.currentTarget.dataset.navigation);
    }

    goToPage(page) {
        const pageContent = [...this.contents].find(p => p.getAttribute("name") == page);
        if (!pageContent) {console.error("Could not find page", page); return;}
        let currentPage = [...this.contents].find(p => p.offsetParent);

        var tl = new gsap.timeline();

        tl.to(currentPage, {alpha: 0, duration: 0.4}, 0);
        tl.add(() => {currentPage.style.display = "none"; pageContent.style.display = "block"}, 0.4);
        tl.fromTo(pageContent, {alpha: 0}, {alpha: 1, duration: 0.4}, 0.4);

        const component = this.bootstrap.components.get(pageContent);
        if (component && typeof component.open == "function") {component.open();}

        return tl;
    }


}