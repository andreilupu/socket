import React from 'react';

import TextField from 'material-ui/TextField';
import Checkbox from 'material-ui/Checkbox';

import Toggle from 'material-ui/Toggle';

class Field extends React.Component {
	constructor(props) {
		// this makes the this
		super(props);
	}

	render() {

		let output = null;
		let config = this.props.config

		switch ( this.props.config.type ) {

			case 'text' : {
				output = <TextField hintText="Hint Text" />
				break;
			}

			case 'checkbox' : {
				output = <Checkbox
						label="Label on the left"
						labelPosition="left"
					/>
				break;
			}

			case 'toggle' :  {
				output = <Toggle
					label="Toggled by default"
					defaultToggled={true}
				/>
				break;
			}

			case 'radio' : {
				console.log( config );
				output = <TextField
						hintText="Radio  Text"
					/>
				break;
			}

			default:
				break
		}

		return <section className="field">
			<label >{config.label}</label>
			{output}
		</section>;
	}
}

export default Field;