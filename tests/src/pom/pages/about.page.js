// The contact page allows users to contact sweet light studios
"use strict";

import {By} from 'selenium-webdriver';
import xpaths from "../../config/xpaths";

var PageBase = require('../page-base');

class AboutPage extends PageBase {
	constructor ( 
		webdriver,
		driver, 
		targetUrl = 'http://microsimulation.pub/about',
		waitTimeout = 10000
		) {
		const titleContents = 'Aims and scope | About | International Journal of Microsimulation';
		super(webdriver, driver, targetUrl, titleContents, waitTimeout);
	}

	async clickAboutLink() {
		const result = await this.driver.findElement(
			By.xpath("//*[contains(text(), 'About')]"), this.waitTimeout);
		result.click();
	}

	async clickSubmitMyResearch() {
		const result = await this.driver.findElement(
			By.xpath("//*[contains(text(), 'Submit my research')]"));
		result.click();
	}

	async clickButton(xpath){
		const result = await  this.driver.findElement(
			By.xpath(xpaths[xpath]));
			result.click();
	}
}

module.exports = AboutPage;
