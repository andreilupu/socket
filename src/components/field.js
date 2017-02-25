import React from 'react';
import TextField from 'material-ui/TextField';
import Checkbox from 'material-ui/Checkbox';

class AdminOptionsField extends React.Component {
	constructor(props) {
		// this makes the this
		super(props);

	}

	render() {

		var ouput = null;

		console.log( this.props.config.type );
		switch ( this.props.config.type ) {

			case 'text' : {
				ouput = <div>
					<TextField hintText="Hint Text" />
				</div>
				break;
			}

			case 'checkbox' : {
				ouput = <Checkbox
						label="Label on the left"
						labelPosition="left"
					/>
				break;
			}

			case 'radio' : {
				ouput = <div>
					<TextField
						hintText="Radio  Text"
					/>
				</div>
				break;
			}


			default:
				break
		}

		return (ouput)
	}
}

export default AdminOptionsField;