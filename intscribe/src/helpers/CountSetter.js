import React, { Component } from "react";

class CountSetter extends Component {
  constructor(props) {
    super(props);
    this.state = { count: "" };

    this.setCount = this.setCount.bind(this);
  }

  componentDidMount() {
    this.setCount();
  }

  setCount() {
    const value = this.props.count;
    if (value >= 1000) {
      let n = value / 1000;
      this.setState({ count: n + "k" });
    } else if (value < 1000) {
      this.setState({ count: value });
    }
  }

  render() {
    return <React.Fragment>{this.state.count}</React.Fragment>;
  }
}

export default CountSetter;
