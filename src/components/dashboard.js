import React from "react";
import ReactDOM from "react-dom";

import lightBaseTheme from 'material-ui/styles/baseThemes/lightBaseTheme';
import MuiThemeProvider from 'material-ui/styles/MuiThemeProvider';
import getMuiTheme from 'material-ui/styles/getMuiTheme';

import {Tabs, Tab} from 'material-ui/Tabs';
import AdminOptionsField from './field.js';

const tabsStyles = {
	barStyle: {
		display: "none"
	}
};

class OptionsDashboard extends React.Component {

	constructor(props) {
		// this makes the this
		super(props);

		// get the current state localized by wordpress
		this.state = adminoptions.state;
		// // This binding is necessary to make `this` work in the callback

		this.config = adminoptions.config || {}
	}

	render() {

		var SectionsList = Object.keys( adminoptions.config ).map(function( key ){

			var section = adminoptions.config[key];

			var tab = <Tab className={"dashboard-tabs__tab-name"} label={section.label} key={key}>
				{Object.keys( section.fields ).map(function( key ){

					var field_config = section.fields[key];

					var output = <fieldset className="field" key={key}>
						<label htmlFor="">{field_config.label}</label>
						<AdminOptionsField config={field_config}/>
					</fieldset>;

					console.debug( field_config );
					return output;
				})}
			</Tab>;

			return tab;
		})

		var output =
			<div>
				<Tabs className={"dashboard-tabs"} >{ SectionsList }</Tabs>
			</div>

		return (output);
	}

	htmlDecode(input) {
		var e = document.createElement('div');
		e.innerHTML = input;
		return e.childNodes.length === 0 ? "" : e.childNodes[0].nodeValue;
	}

	update_local_state($state) {
		this.setState($state, function () {
			jQuery.ajax({
				url: adminoptions.wp_rest.root + 'adminoptions/v1/react_state',
				method: 'POST',
				beforeSend: function (xhr) {
					xhr.setRequestHeader('X-WP-Nonce', adminoptions.wp_rest.nonce);
				},
				data: {
					'adminoptions_nonce': adminoptions.wp_rest.adminoptions_nonce,
					state: this.state
				}
			}).done(function (response) {
				console.log(response);
			});
		});
	}

	add_notices = (state) => {
		var components = [];
		var install_data = JSON.parse(adminoptions.install_data);

		return components;
	}
}

// Handle active tab hack
window.requestAnimationFrame(function () {
	var tabs = jQuery('.dashboard-tabs__tab-name');

	jQuery('.dashboard-tabs__tab-name:first-of-type').addClass('is-active');
	tabs.on('click', function () {
		tabs.removeClass('is-active');
		jQuery(this).addClass('is-active');
	});
});

const OptionsPage = () => (
	<MuiThemeProvider muiTheme={getMuiTheme(lightBaseTheme)}>
		<OptionsDashboard />
	</MuiThemeProvider>
);

export default OptionsPage;