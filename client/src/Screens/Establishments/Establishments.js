import React, { Component } from 'react';
import Promise from "bluebird";

class Establishments extends Component {
    constructor(props) {
        super(props);
        this.state = {
            name : null,
            address : null,
        }
    }

    handleFormInput(k, v) {
        this.setState({
            [k] : v
        });
    }

    render() {
        return (
            <main>
                <div class={"form-input"}>
                    <label>
                    <input
                        value={this.state.name}
                        onChange={(e) => {
                            this.handleFormInput('name', e.target.value)
                        }}
                    />
                    </label>
                </div>
                <div class={"form-input"}>
                    <label>
                    <input
                        value={this.state.address}
                        onChange={(e) => {
                            this.handleFormInput('address', e.target.value)
                        }}
                    />
                    </label>
                </div>
                <h1>WIP - this page will let you search for establishments when ompleted</h1>
            </main>
        );
    }
}

export default Establishments;
