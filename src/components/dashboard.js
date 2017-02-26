import React from "react";
import ReactDOM from "react-dom";

// import semantic from './semantic.min.js';

import Sections from './sections';

import { Grid, Image } from 'semantic-ui-react'
import { Container, Header } from 'semantic-ui-react'
import { Text, Button, Checkbox, Form } from 'semantic-ui-react'

class AdminOptionsDashboard extends React.Component {

	constructor(props) {
		// this makes the this
		super(props);

		// get the current state localized by wordpress
		this.state = adminoptions.state;

		this.handleChange = this.handleChange.bind(this);
	}

	render() {
		let component = this;
		return <div>
			<Grid>{ Object.keys( adminoptions.config ).map(function( grid_key ){
				if ( typeof grid_key === "undefined" ) {
					return false;
				}

				var section_config = adminoptions.config[grid_key];

				// default grid sizes, doc this
				var sizes = { ...{ computer: 8, tablet: 16 }, ...section_config.sizes };

				var section = <Grid.Column key={grid_key} computer={sizes.computer} tablet={sizes.tablet} mobile={sizes.mobile}>

					<Header as='h2' key={grid_key} content={section_config.label} subheader={section_config.desc} />

					<Form >
					{ Object.keys( section_config.items ).map(function( field_key ){

						var field = section_config.items[field_key];

						var output = null;

						switch ( field.type ) {
							case 'text' : {
								output = <Form.Field key={field_key}>
									<label>{field.label}</label>
									<input placeholder='First Name' onInput={component.handleChange} />
								</Form.Field>
								break;
							}

							case 'checkbox' : {
								output = <Form.Field key={field_key}>
									<label>{field.label}</label>
									<Checkbox placeholder='First Name' onChange={component.handleChange} />
								</Form.Field>
								break;
							}

							case 'toggle' : {
								output = <Form.Field key={field_key}>
									<label>{field.label}</label>
									<Checkbox toggle placeholder='First Name' onChange={component.handleChange} />
								</Form.Field>
								break;
							}

							default:
								break
						}

						return output
					})}
					</Form>
				</Grid.Column>

				return section
			}) }</Grid>
		</div>
	}

	htmlDecode(input) {
		var e = document.createElement('div');
		e.innerHTML = input;
		return e.childNodes.length === 0 ? "" : e.childNodes[0].nodeValue;
	}

	handleChange( e ) {

		console.debug(e);

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

export default (AdminOptionsDashboard);