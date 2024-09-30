import { gsap } from "gsap";
import { ScrollTrigger } from "gsap/ScrollTrigger";
import { StaticComponent } from "libs";

export class Formation28Component extends StaticComponent {
  constructor(e, b) {
    super(e, b);
    this.query = this.query.bind(this);
    this.loadMore = this.loadMore.bind(this);

    this.search = this.element.querySelector(".search>input");
    this.grid = this.element.querySelector(".grid>div>.content");
    this.more = this.element.querySelector(".link-plus");

    this.offices = {
      elem: this.element.querySelector(".offices"),
      content: "",
      height: 1,
      opened: true,
    };
    this.types = {
      elem: this.element.querySelector(".types"),
      content: "",
      height: 1,
      opened: true,
    };

    this.filters = document.querySelector(".filters");

    this.offices.changeByList = this.changeByList.bind(this, this.offices);
    this.offices.changeBySelect = this.changeBySelect.bind(this, this.offices);
    this.offices.toggle = this.toggleFilter.bind(this, this.offices);

    this.types.changeByList = this.changeByList.bind(this, this.types);
    this.types.changeBySelect = this.changeBySelect.bind(this, this.types);
    this.types.toggle = this.toggleFilter.bind(this, this.types);

    this.offices.value = this.offices.elem.querySelector(".value");
    this.types.value = this.types.elem.querySelector(".value");

    this.offices.select = this.offices.elem.querySelector("select");
    this.types.select = this.types.elem.querySelector("select");

    this.offices.ul = this.offices.elem.querySelector("ul");
    this.types.ul = this.types.elem.querySelector("ul");

    this.offices.li = Array.from(this.offices.ul.querySelectorAll("li"));
    this.types.li = Array.from(this.types.ul.querySelectorAll("li"));

    this.getFormationIds();
  }

  attach() {
    this.search.addEventListener("keyup", this.seachByName);

    this.offices.li.forEach((li) => {
      li.addEventListener("click", this.offices.changeByList);
    });
    this.types.li.forEach((li) => {
      li.addEventListener("click", this.types.changeByList);
    });

    this.offices.elem.addEventListener("click", this.offices.toggle);
    this.types.elem.addEventListener("click", this.types.toggle);

    this.offices.select.addEventListener("change", this.offices.changeBySelect);
    this.types.select.addEventListener("change", this.types.changeBySelect);

    this.more && this.more.addEventListener("click", this.loadMore);

    this.viewport.on("resize", this.handleResize);
    this.viewport.on("media", this.handleMedia);

    this.addHoursEvents();

    this.toggleFilter(this.offices);
    this.toggleFilter(this.types);

    this.handleMedia();
  }

  detach() {
    this.search.removeEventListener("keyup", this.seachByName);

    this.offices.li.forEach((li) => {
      li.removeEventListener("click", this.types.changeByList);
    });
    this.types.li.forEach((li) => {
      li.removeEventListener("click", this.types.changeByList);
    });

    this.offices.select.removeEventListener("change", this.offices.changeBySelect);
    this.types.select.removeEventListener("change", this.types.changeBySelect);

    this.more && this.more.removeEventListener("click", this.loadMore);

    this.removeHoursEvents();

    this.viewport.off("resize", this.handleResize);
    this.viewport.off("media", this.handleMedia);

    this.st.kill();
  }

  handleResize = () => {
    this.hoursResize();

    let last = this.offices.ul.style.height;
    this.offices.ul.style.height = "";
    this.offices.height = this.offices.ul.getBoundingClientRect().height - 78;
    this.offices.ul.style.height = last;

    last = this.types.ul.style.height;
    this.types.ul.style.height = "";
    this.types.height = this.types.ul.getBoundingClientRect().height - 78;
    this.types.ul.style.height = last;
  };

  handleMedia = () => {
    if (this.st) this.st.kill();

    if (this.viewport.medias.desktop) {
      this.st = ScrollTrigger.create({
        trigger: this.grid,
        start: "top +=140px",
        end: "bottom center",
        pin: this.filters,
        pinSpacing: false,
      });
    }
  };

  addHoursEvents() {
    this.hours = Array.from(this.element.querySelectorAll(".hours")).map((elem) => {
      return {
        elem,
        btn: elem.querySelector(".item"),
        arrow: elem.querySelector(".icon-arrow-bottom"),
        content: elem.querySelector(".all-dates"),
        toggle: null,
        opened: true,
        height: 1,
      };
    });

    this.hoursResize();

    this.hours.forEach((hour) => {
      hour.toggle = this.handleHoursClick.bind(this, hour);
      hour.btn.addEventListener("click", hour.toggle);
      this.closeHours(hour);
    });
  }

  removeHoursEvents() {
    this.hours.forEach((hour) => {
      hour.btn.removeEventListener("click", hour.toggle);
    });
  }

  toggleFilter(filter) {
    if (filter.opened) {
      this.closeFilter(filter);
    } else {
      this.closeFilter(this.offices);
      this.closeFilter(this.types);

      this.openFilter(filter);
    }
  }

  openFilter(filter) {
    if (filter.opened) return;

    filter.ul.style.height = Math.min(Math.min(filter.height, 400), this.viewport.height - 284) + "px";
    filter.elem.classList.add("active");
    filter.opened = true;
  }
  closeFilter(filter) {
    if (!filter.opened) return;

    filter.ul.style.height = "0px";
    filter.elem.classList.remove("active");
    gsap.to(filter.ul, { scrollTo: 0, duration: 0.5, ease: "power2.out" });
    filter.opened = false;
  }

  getFormationIds() {
    this.formations = Array.from(this.grid.querySelectorAll(".content>.item"));
    this.formationIds = this.formations.map((elem) => elem.getAttribute("data-id"));
  }

  seachByName = () => {
    this.query(true);
  };

  changeByList(filter, event) {
    filter.content = filter.select.value = event.srcElement.getAttribute("data-value");
    filter.value.innerHTML = event.srcElement.innerHTML;
    this.query(true);
  }

  changeBySelect(filter) {
    filter.content = filter.select.value;
    filter.value.innerHTML = filter.select.innerHTML;
    this.query(true);
  }

  loadMore() {
    this.query();
  }

  async query(reset = false) {
    this.getFormationIds();

    let response = await fetch(ADMIN_AJAX_URL, {
      method: "POST",
      credentials: "include",
      headers: { "Content-type": "application/x-www-form-urlencoded" },
      body: new URLSearchParams({
        action: "capstan_filter_formations",
        search: this.search.value,
        office: this.offices.content,
        type: this.types.content,
        notin: !reset ? this.formationIds : "",
      }).toString(),
    });

    const result = await response.json();
    this.update(result, reset);
  }

  update(data, reset = false) {
    if (reset) this.grid.innerHTML = "";
    this.removeHoursEvents();

    data.formations.forEach((formation) => {
      let content = "";
      content += `

			<div class="item" data-id="${formation.ID}">
				<div class="image">
					<picture>
						<img src="${formation.image}" alt="${formation.alt}" draggable="false">
					</picture>
				</div>
				<div class="text">
					<div class="date">${formation.subtitle}</div>
					<a href="${formation.url}"><div class="title"><span class="t-word">${formation.title}</span></div></a>
					<div class="description">${formation.description}</div>
					<div class="infos">
						<div class="item"><span class="name">Bureau</span><span class="value">${formation.office}</span></div>`;

      if (formation.type == "multi") {
        content += `
							<div class="hours">
								<div class="item">
									<span class="name">Horaires</span>
									<span class="value">${formation.dates.length} dates disponibles</span>
									<i class="icon-arrow-bottom"></i>
								</div>
								<div class="all-dates">`;
        formation.dates.forEach((date) => {
          content += `
									<div class="single-date">
										<span class="left">${date[0]}</span>
										<span class="right">${date[1]}</span>
									</div>`;
        });
        content += `
								</div>
							</div>`;
      } else {
        content += `
						<div class="item"><span class="name">Horaires</span><span class="value">${formation.dates}</span></div>`;
      }
      content += `
					</div>
				</div>
			</div>`;

      this.grid.innerHTML += content;
    });

    if (this.more) {
      if (data.havemore) this.more.style.display = "block";
      else this.more.style.display = "none";
    }

    this.addHoursEvents();
    this.getFormationIds();
    this.handleMedia();
  }

  handleHoursClick(hour, e) {
    if (hour.opened) this.closeHours(hour);
    else this.openHours(hour);
  }

  closeHours(hour) {
    hour.content.style.height = "0px";
    hour.arrow.style.transform = "";
    hour.opened = false;
  }

  openHours(hour) {
    hour.content.style.height = hour.height + "px";
    hour.arrow.style.transform = "rotate(180deg)";
    hour.opened = true;
  }

  hoursResize() {
    this.hours.forEach((hour) => {
      let last = hour.content.style.height;
      hour.content.style.height = "";
      hour.height = hour.content.getBoundingClientRect().height;
      hour.content.style.height = last;
    });
  }
}
