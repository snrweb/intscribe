import React, { Component } from "react";

class TimeDiff extends Component {
  constructor(props) {
    super(props);
    this.state = {
      date: ""
    };

    this.getDate = this.getDate.bind(this);
  }

  componentDidMount() {
    this.getDate();
  }

  getDate() {
    let MS = 1000 * 60 * 60 * 24 * 7 * 4;
    let rawDate = this.props.date;
    let a = new Date(rawDate);
    let b = new Date();

    let utc1 = Date.UTC(
      a.getFullYear(),
      a.getMonth(),
      a.getDate(),
      a.getHours(),
      a.getMinutes()
    );

    let utc2 = Date.UTC(
      b.getFullYear(),
      b.getMonth(),
      b.getDate(),
      b.getHours() + 1,
      b.getMinutes()
    );

    let date = Math.floor((utc2 - utc1) / MS);

    let DATEDIFF;

    if (date > 0) {
      DATEDIFF = new Date(rawDate.split(" ")[0]).toDateString();
    } else {
      let MS = 1000 * 60 * 60 * 24;
      date = Math.floor((utc2 - utc1) / MS);
      if (date > 0) {
        DATEDIFF = date + " days ago";
      } else {
        let MS = 1000 * 60 * 60;
        date = Math.floor((utc2 - utc1) / MS);
        if (date > 0) {
          DATEDIFF = date + " hours ago";
        } else {
          let MS = 1000 * 60;
          date = Math.floor((utc2 - utc1) / MS);
          if (date > 0) {
            DATEDIFF = date + " mins ago";
          } else {
            DATEDIFF = "Just now";
          }
        }
      }
    }

    this.setState({ date: DATEDIFF });
  }

  render() {
    return <React.Fragment>{this.state.date}</React.Fragment>;
  }
}

export default TimeDiff;
