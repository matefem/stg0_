import {DefaultComponent} from 'libs';
import ajaxFetcher from '../../../tools/AjaxFetcher';


export class AccountLogout extends DefaultComponent {
    constructor(e, b){
        super(e, b);
    }

    attach() {
        this.element.addEventListener("click", this.onClickLogout);
    }

    detach() {
        this.element.removeEventListener("click", this.onClickLogout);
    }

    onClickLogout = async (e) => {
        const result = await ajaxFetcher.call("capstan_account_logout", new FormData());

        this.bootstrap.components.get(document.querySelector("header")).refreshAccountStatus();

        document.location.href = this.element.dataset.redirect;
    }

}