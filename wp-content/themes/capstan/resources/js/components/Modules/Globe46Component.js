import { StaticComponent } from 'libs';
import { gsap } from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';

import Globekit from '../../libs/WireframeGlobe.js';
import {
  GlobeKitView, PointGlobe, Points, Atmosphere, Background, GKUtils, CalloutManager, Callout, Arc, CalloutDefinition
} from '../../libs/globekit/globekit.esm.js';

// const path = 'http://localhost/capstan-0421/wp-content/themes/capstan/resources/assets/globekit/'
const path = '/wp-content/themes/capstan/resources/assets/globekit/'

const apiKey = 'gk_96cec9a051f6ca67d9a9332cde1d003d1b3071522b677b2b9c402ee19551e3c26a294830ab0fb2bc220e176631201fe413a7e9508951aac1a52326c99ddb6d31';
const textures = {
  noise: path + 'clouds.png',
  bg: path + 'bg.png',
  disk: path + 'disk.png'
};

const offices = [{"name": "pbbr","address": "Av. Liberdade, 110, 6º - 1250 - 146 Lisboa - Portugal","city": "Lisboa","country": "Portugal","lat": 38.7077507,"long": -9.1365919},{"name": "Troillet Meier Raetzo","address": "Rue de Lyon, 77 - 1203 Geneve Suisse","city": "Geneve","country": "Switzerland","lat": 46.2017559,"long": 6.1466014},{"name": "Advokatfirmaet Hjort DA","address": "Akersgata 51, 0180 Oslo, Norway","city": "Oslo","country": "Norway","lat": 59.9133301,"long": 10.7389701},{"name": "Elmzell Advokatbyrå","address": "Rehnsgatan 11, 113 57 Stockholm","city": "Stockholm","country": "Sweden","lat": 59.3251172,"long": 18.0710935},{"name": "Blesi & Papa","address": "Usteristrasse 10, am Löwenplatz P.O. Box, CH-8021 Zürich","city": "Zürich","country": "Switzerland","lat": 47.3744489,"long": 8.5410422},{"name": "Bronsgeest Deur Advocaten","address": "IJdok 29, 1013 MM Amsterdam, The Netherlands","city": "Amsterdam","country": "Netherlands","lat": 52.3727598,"long": 4.8936041},{"name": "Lewis Silkin (Ireland)","address": "26 Lower Baggot Street, Dublin, Ireland","city": "Dublin","country": "Ireland","lat": 53.3497645,"long": -6.2602732},{"name": "Al Tamimi & Co (UAE)","address": "PO Box 9275, 6th Floor, Building 4 East, Sheikh Zayed Rd, Dubai","city": "Dubai","country": "United Arab Emirates","lat": 25.2653471,"long": 55.2924914},{"name": "Bener Law Office","address": "Büyükdere Cad. No: 193/4, 34394 Levent, Istanbul, Turkey","city": "Istanbul","country": "Turkey","lat": 41.0096334,"long": 28.9651646},{"name": "Al Tamimi & Co (Saudi Arabia)","address": "Zamil House, 9th Floor Prince Turkey Street Corniche District P.O. Box 1227 Al Khobar, Saudi Arabia 31952","city": "Al Khobar","country": "Saudi Arabia","lat": 26.3039999,"long": 50.1960237},{"name": "COBALT (Estonia)","address": "Kawe Plaza, Pärnu mnt 15, 10141 Tallinn, Estonia","city": "Tallinn","country": "Estonia","lat": 59.4372155,"long": 24.7453688},{"name": "COBALT (Belarus)","address": "Prospekte Pobeditelei 100, Minsk, Belarus","city": "Minsk","country": "Belarus","lat": 53.902334,"long": 27.5618791},{"name": "Al Tamimi & Co (Bahrain)","address": "Suite 1304, Office 13B, 13th Floor, Building 1459, Block 346, West Tower, Bahrain Financial Harbour, PO Box 60380, Manama, Bahrain","city": "Manama","country": "Bahrain","lat": 26.2235041,"long": 50.5822436},{"name": "Nestor Nestor Diculescu Kingston Petersen (NNDKP)","address": "Strada Barbu Văcărescu nr. 201, Clădirea Globalworth Tower Et. 18, Bucharest 020276, Romania","city": "Bucharest","country": "Romania","lat": 44.4361414,"long": 26.1027202},{"name": "Estudio Olaechea","address": "Bernardo Monteagudo 201, San Isidro, Lima 27, Perú","city": "Lima","country": "Peru","lat": -12.0621065,"long": -77.0365256},{"name": "Rajah & Tann Thailand","address": "12a, 973 President Tower, 12th Floor Units 12F Phloen Chit Rd, Bangkok 10330, Thailand","city": "Bangkok","country": "Thailand","lat": 13.8245796,"long": 100.6224463},{"name": "Mathews Dinsdale","address": "RBC Centre, Suite 3600, 155 Wellington Street West, Toronto, ON M5V 3H1, Canada - Other offices","city": "Toronto","country": "Canada","lat": 43.6534817,"long": -79.3839347},{"name": "Yulchon LLC","address": "Textile Center 12F, 518 Teheran-ro, Daechi-dong, Gangnam-gu - Seoul 135-713, Korea","city": "Seoul","country": "South Korea","lat": 37.5666791,"long": 126.9782914},{"name": "Basham, Ringe y Correa S.C.","address": "Paseo de los Tamarindos No. 400-A, Piso 9 Col. Bosques de las Lomas, C.P. 05120, Mexico City, Mexico","city": "Mexico City","country": "Mexico","lat": 19.4326296,"long": -99.1331785},{"name": "Funes de Rioja & Asociados","address": "Av Eduardo Madero 942, 11th floor, C1106ACW Buenos Aires, Argentina","city": "Buenos Aires","country": "Argentina","lat": -36.3789925,"long": -60.3855889},{"name": "D’Empaire","address": "Edificio Bancaracas, Plaza La Castellana 1060, Venezuela","city": "Caracas","country": "Venezuela","lat": 10.506098,"long": -66.9146017},{"name": "FordHarrison LLP","address": "271 17th Street NW, Suite 1900 - Atlanta, Georgia 30363","city": "Atlanta","country": "United States","lat": 33.7489924,"long": -84.3902644},{"name": "Vasil Kisil & Partners","address": "Street B. Khmelnytsky, 17/52 A - BC Leonardo, 6th floor - Kyiv 01054 Ukraine","city": "Kyiv","country": "Ukraine","lat": 50.4500336,"long": 30.5241361},{"name": "Herzog Fox & Neeman","address": "4 Weizmann Street, Tel Aviv 6423904, Israel","city": "Tel Aviv","country": "Israel","lat": 32.0852997,"long": 34.7818064},{"name": "Anderson Mori & Tomotsune","address": "Otemaci Park Building,1-1-1 Otemachi, Chiyoda-ku, Tokyo 100-8136, Japan","city": "Tokyo","country": "Japan","lat": 35.6828387,"long": 139.7594549},{"name": "Toffoletto De Luca Tamajo","address": "Via Rovello 12 - 20121, Milano, Italy","city": "Milano","country": "Italy","lat": 45.4668,"long": 9.1905},{"name": "Kochhar & Co.","address": "11th Floor, Tower A, DLF Towers Jasola - Jasola District Center - New Delhi - 110 025 India","city": "New Delhi","country": "India","lat": 28.6138954,"long": 77.2090057},{"name": "CLV Partners","address": "Tartsay Vilmos u. 3, 1121, Budapest, Hungary","city": "Budapest","country": "Hungary","lat": 47.48138955,"long": 19.14609412691246},{"name": "Lewis Silkin (Hong Kong)","address": "Unit 1302, 13/F, Dina House - Ruttonjee Centre - 11 Duddell Street - Central - Hong Kong","city": "Hong Kong","country": "Hong Kong","lat": 22.350627,"long": 114.1849161},{"name": "KREMALIS LAW FIRM","address": "Kirillou Loukareos 35, Athens 114 75 - Greece","city": "Athens","country": "Greece","lat": 37.9839412,"long": 23.7283052},{"name": "Capstan Avocats","address": "83, rue La Boétie 75008 Paris","city": "Paris","country": "France","lat": 48.8566969,"long": 2.3514616},{"name": "Dittmar & Indrenius","address": "Pohjoisesplanadi 25 A - FI-00100 Helsinki, Finland","city": "Helsinki","country": "Finland","lat": 60.1674881,"long": 24.9427473},{"name": "Norrbom Vinding","address": "Dampfærgevej 26, 2100 Copenhagen, Denmark","city": "Copenhagen","country": "Denmark","lat": 55.6867243,"long": 12.5700724},{"name": "Kliemt.HR Lawyers","address": "Speditionstraße 21 - D-40221 Düsseldorf","city": "Düsseldorf","country": "Germany","lat": 51.2254018,"long": 6.7763137},{"name": "Rajah & Tann Singapore","address": "9 Straits View #06-07 Marina One West Tower, Singapore 018937","city": "Singapore","country": "Singapore","lat": 1.2904753,"long": 103.8520359},{"name": "Randl Partners, advokátní kancelář, s.r.o.","address": "Tetris Office Building - Budějovická 1550/15a - CZ 140 00, Prague 4","city": "Prague","country": "Czech Republic","lat": 50.0874654,"long": 14.4212535},{"name": "Sackers","address": "20 Gresham Street - London - EC2V 7JE","city": "London","country": "United Kingdom","lat": 51.5153381,"long": -0.0957187},{"name": "Lewis Silkin","address": "5 Chancery Lane - Clifford's Inn - London - EC4A 1BL | DX 182 CHANCERY LANE","city": "London","country": "United Kingdom","lat": 51.5142956,"long": -0.113279},{"name": "Sagardoy Abogados","address": "c/ Tutor, 27 - 28008 Madrid, Spain","city": "Madrid","country": "Spain","lat": 40.4167047,"long": -3.7035825},{"name": "ŠELIH & PARTNERJI Law Firm","address": "Komenskega ulica 36 - 1000 Ljubljana, Slovenia","city": "Ljubljana","country": "Slovenia","lat": 46.0499803,"long": 14.5068602},{"name": "NITSCHNEIDER & PARTNERS","address": "Lazaretská 12 - 811 08 Bratislava - Slovakia","city": "Bratislava","country": "Slovakia","lat": 48.1516988,"long": 17.1093063},{"name": "Karanovic & Partners","address": "Resavska 23 - 11000 Belgrade, Serbia","city": "Belgrade","country": "Serbia","lat": 44.8178131,"long": 20.4568974},{"name": "Raczkowski","address": "Bonifraterska 17, 00-203 Warsaw, Poland","city": "Warsaw","country": "Poland","lat": 52.2319581,"long": 21.0067249},{"name": "Kiely Thompson Caisley","address": "Level 10, 188 Quay St, Auckland, New Zealand","city": "Auckland","country": "New Zealand","lat": -36.8433264,"long": 174.7630263},{"name": "Blom Veugelers Zuiderman Advocaten","address": "Schiedamseweg 53A, 3134 BB Vlaardingen, The Netherlands","city": "Vlaardingen","country": "Netherlands","lat": 51.9075958,"long": 4.3399618},{"name": "George Z. Georgiou <br>& Associates LLC","address": "1 Iras Street - 1060 Nicosia, Cyprus","city": "Nicosia","country": "Cyprus","lat": 35.1677369,"long": 33.3621672},{"name": "Brigard Urrutia","address": "Calle 70 Bis No. 4 - 41 Bogotá, Colombia","city": "Bogotá","country": "Colombia","lat": 4.6519899,"long": -74.0565586},{"name": "Divjak Topić <br>Bahtijarević & Krka","address": "EUROTOWER, Ivana Lučića 2A / 18th Floor, 10000 Zagreb, Croatia","city": "Zagreb","country": "Croatia","lat": 4.6533326,"long": -74.083652},{"name": "Fangda Partners","address": "27/F, North Tower, Beijing Kerry Centre - 1 Guanghua Road, Chaoyang District - Beijing 100020, China","city": "Beijing","country": "China","lat": 40.190632,"long": 116.412144},{"name": "Munita & Olavarría","address": "Alcantara 200, Las Condes, Santiago, Chile","city": "Santiago","country": "Chile","lat": -33.4377756,"long": -70.6504502},{"name": "BOYANOV & Co.","address": "82, Patriarch Evtimii Blvd., 1463 Sofia, Bulgaria","city": "Sofia","country": "Bulgaria","lat": 42.6978634,"long": 23.3221789},{"name": "Claeys & Engels","address": "Boulevard du Souverain 280, 1160 Brussels, Belgium","city": "Brussels","country": "Belgium","lat": 50.83879505,"long": 4.375304132256188},{"name": "Corrs Chambers Westgarth","address": "Level 25, 567 Collins Street, Melbourne VIC 3000","city": "Melbourne","country": "Australia, Papua New Guinea","lat": -37.8142176,"long": 144.9631608},{"name": "Ganado Advocates","address": "171, Old Bakery Street, Valletta VLT 1455, Malta","city": "Valletta","country": "Malta","lat": 35.8989818,"long": 14.5136759},{"name": "CASTEGNARO","address": "67, rue Ermesinde, L-1469 Luxembourg","city": "Luxembourg","country": "Luxembourg","lat": 49.6112768,"long": 6.129799},{"name": "COBALT (Lithuania)","address": "Lvovo 25, LT-09320 Vilnius","city": "Vilnius","country": "Lithuania","lat": 54.6870458,"long": 25.2829111},{"name": "COBALT (Latvia)","address": "Marijas iela 13 k-2, LV-1050, Riga, Latvia","city": "Riga","country": "Latvia","lat": 56.9500002,"long": 24.1211754},{"name": "AEQUITAS Law Firm","address": "47 Abai Ave., Office 2, Almaty 050000, Republic of Kazakhstan","city": "Almaty","country": "Kazakhstan","lat": 43.2214719,"long": 76.847242},{"name": "Veirano Advogados","address": "Av Presidente Wilson, 231, 25º andar - Centro - Rio de Janeiro, RJ, Brazil - 20030-021","city": "Rio de Janeiro","country": "Brazil","lat": -22.9110137,"long": -43.2093727},{"name": "Schima Mayer Starlinger","address": "Trabrennstraße 2B, A-1020 Vienna","city": "Vienna","country": "Austria","lat": 48.2124507,"long": 16.4101008}];
const generateOfficesGeoJson = () => {
  const geojson = {
    type: 'FeatureCollection',
    features: [],
  };

  offices.forEach(office => {
    const feature = {
      type: 'Feature',
      properties: {

      },
      geometry: {
        type: 'Point',
        coordinates: [],
      },
    };
    
    feature.geometry.coordinates = [office.long, office.lat];

    // feature.properties.mythicalCreatureSightings = 50;
    feature.properties.name = office.name;
    feature.properties.address = office.city + ' - ' + office.country;

    geojson.features.push(feature);
  })

  return geojson;
};

// Generate some random Geojson
const randomGeojson = generateOfficesGeoJson();

class CapstanGlobeKit {
  constructor(canvas, callout) {
    this.gkOptions = {
      apiKey,
      wasmPath: path + 'gkweb_bg.wasm',
      attributes: {
        alpha: false,
      },
    };

    this.gkview = new GlobeKitView(canvas, this.gkOptions);
    this.calloutManager = new CalloutManager(callout);
    this.gkview.registerCalloutManager(this.calloutManager);

    this.background = new Background(textures.bg);
    this.gkview.addDrawable(this.background);

    this.atmosphere = new Atmosphere({
      texture: textures.disk,
    });

    this.arcs = new Arc();
    this.gkview.addDrawable(this.arcs);

    this.atmosphere.nScale = 1.03;
    this.gkview.addDrawable(this.atmosphere);

    fetch(path + 'pointglobe.bin')
      .then((res) => res.arrayBuffer())
      .then((data) => {
        const pointglobeParams = {
          pointSize: 0.003,
          randomPointSizeVariance: 0.002,
          randomPointSizeRatio: 0.1,
          minPointAlpha: 0.0,
          minPointSize: 0.006,
          color: '#dddddd'
        };
        this.pointglobe = new PointGlobe(textures, data, pointglobeParams);
        this.pointglobe.setInteractive(true, true, false);
      })
      .then(() => {
        this.gkview.addDrawable(this.pointglobe, () => {
          this.gkview.startDrawing();
        });

        this.points = new Points();
        this.points.transform = (element, point) => {
          point.color = GKUtils.hexToRGBA('#ff0000', Math.random() * 0.3 + 0.7, true); //GKUtils.lerpColor('#ff0000', '#ff0000', element.properties.mythicalCreatureSightings / 30);
          point.size = Math.random() * 3 + 7
          return point;
        };

        this.points.addGeojson(randomGeojson);
        this.points.setInteractive(true, true, false);
        this.gkview.addDrawable(this.points);
      });
  
      this.gkview.onSelection = (list) => {
        // Uncomment this line to see the list object
        // console.log(list);

        list.drawables.forEach((el) => {
          if (el.obj.id === this.points.id) {
            if (el.selection !== undefined) {
              const sel = el.selection[0][0];

              this.calloutManager.removeAllCallouts();
              this.arcs.removeAllArcs();

              this.calloutManager.addCallout(new CalloutDefinition(sel.lat, sel.lon, OfficeCallout, sel.properties));

              const arcParams = {
                from: [sel.lat, sel.lon],
                to: [41, -111],
                startColor: '#ffffff',
                startAlpha: 1.0,
                midWidth: 4,
              };
              this.arcs.addArc(arcParams, 1000);
            } else {
              this.arcs.removeAllArcs();
              this.calloutManager.removeAllCallouts();
            }
          }
        });
      };
  }

}

class OfficeCallout extends Callout {
  createElement() {
    const div = document.createElement('div');
    div.className = 'office-callout';
    div.innerHTML = `<div class="callout-container">
      <div class="wrapper">
        <div class="name">${this.definition.data.name}</div>
        <p class="address">${this.definition.data.address}</p>
      </div>
      <div class="arc"><span class="pointer"></span><span class="ref"></span></div>
    </div>`;
    return div;
  }
  setPosition(position) {
    this.element.style.transform = `translate(${(position.screen.x + 50).toFixed(1)}px, ${(position.screen.y - 60).toFixed(0)}px)`;
  }
}

export class Globe46Component extends StaticComponent{
	constructor(e, b){
		super(e, b);

		this.canvas = this.element.querySelector('canvas');
    this.callout = this.element.querySelector('.globe-callout-manager');
  }

	attach(){
		this.viewport.on('resize', this.handleResize);

		this.tl = ScrollTrigger.create({
			trigger: this.element,
			onEnter: () => {
				// this.globe.open()
			},
			onEnterBack: () => {
				// this.globe.open()
			},
			onLeave: () => {
				// this.globe.close()
			},
			onLeaveBack: () => {
				// this.globe.close()
			}
		});

		this.gk = new CapstanGlobeKit(this.canvas, this.callout);
	}

	detach(){
	}

	handleResize = () => {
	}
}