import React from "react";
import {Tabs, Tab} from 'material-ui/Tabs';

import Field from './field.js';

class Sections extends React.Component {
	constructor(props) {
		// this makes the this
		super(props);
	}

	render() {

		return <div>
			<Tabs className={"dashboard-tabs"} >{ Object.keys( adminoptions.config ).map(function( tab_key ){

				if ( typeof tab_key === "undefined" ) {
					return false;
				}

				var section = adminoptions.config[tab_key];

				var tab =
					<Tab className={"dashboard-tabs__tab-name"} label={section.label} key={tab_key}>

						{Object.keys( section.fields ).map(function( field_key ){

							var field_config = section.fields[field_key];

							return <Field key={field_key} config={field_config}/>;
						})}
					</Tab>;

				return tab;
			}) }</Tabs>
		</div>
	}
}

export default Sections;