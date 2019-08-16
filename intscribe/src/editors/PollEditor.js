import React, { Component } from "react";

class PollEditor extends Component {
  constructor(props) {
    super(props);
    this.state = {
      userInterests: [],
      post_title: "",
      post_int: "",
      option_one: "",
      option_two: "",
      option_three: "",
      option_four: "",
      poll_editor_mins: 0,
      poll_editor_hours: 0,
      poll_editor_days: 0,
      csrf_token: "",
      count: 2
    };

    this.onSubmit = this.onSubmit.bind(this);
    this.onChange = this.onChange.bind(this);
    this.alertMsg = this.alertMsg.bind(this);
    this.addOptions = this.addOptions.bind(this);
    this.removeOptions = this.removeOptions.bind(this);
  }

  componentDidMount() {
    fetch(`${this.props.apiROOT}post/insertPoll`)
      .then(res => res.json())
      .then(data => {
        this.setState({
          userInterests: data.userInterests,
          csrf_token: data.csrf_token
        });
      });
  }

  alertMsg(elem, className, errorMessage) {
    elem.classList.add(className);
    elem.innerHTML = errorMessage;
  }

  addOptions(e) {
    e.preventDefault();
    if (this.state.count !== 4) {
      this.setState({ count: this.state.count + 1 });
    }
  }

  removeOptions(e) {
    e.preventDefault();
    if (this.state.count !== 2) {
      this.setState({ count: this.state.count - 1 });
    }
  }

  onChange(e) {
    this.setState({ [e.target.name]: e.target.value });
    e.target.parentElement.querySelector(".char-counter").innerHTML =
      e.target.value.length;
  }

  onSubmit(e) {
    e.preventDefault();

    if (this.state.post_title.trim().length < 10) {
      let alertElem = document.querySelector("#alert");
      this.alertMsg(alertElem, "error-alert", "Question is too short");
      return;
    }

    if (this.state.post_title.trim().length > 200) {
      let alertElem = document.querySelector("#alert");
      this.alertMsg(
        alertElem,
        "error-alert",
        "Question contains more than 200 characters"
      );
      return;
    }

    if (
      this.state.post_int.trim() === "Pick an interest" ||
      this.state.post_int.trim() === ""
    ) {
      let alertElem = document.querySelector("#alert");
      this.alertMsg(alertElem, "error-alert", "Select interest");
      return;
    }

    if (
      this.state.option_one.trim() === "" ||
      this.state.option_two.trim() === ""
    ) {
      let alertElem = document.querySelector("#alert");
      this.alertMsg(
        alertElem,
        "error-alert",
        "Poll must contain at least two options"
      );
      return;
    }

    if (
      this.state.poll_editor_mins === 0 &&
      this.state.poll_editor_hours === 0 &&
      this.state.poll_editor_days === 0
    ) {
      let alertElem = document.querySelector("#alert");
      this.alertMsg(
        alertElem,
        "error-alert",
        "You have not selected poll duration"
      );
      return;
    }

    let formdata = new FormData();
    formdata.append("post_title", this.state.post_title);
    formdata.append("post_int", this.state.post_int);
    formdata.append("option_one", this.state.option_one);
    formdata.append("option_two", this.state.option_two);
    formdata.append("option_three", this.state.option_three);
    formdata.append("option_four", this.state.option_four);
    formdata.append("poll-editor-mins", this.state.poll_editor_mins);
    formdata.append("poll-editor-hours", this.state.poll_editor_hours);
    formdata.append("poll-editor-days", this.state.poll_editor_days);
    formdata.append("csrf_token", this.state.csrf_token);
    formdata.append("isJSeditor", 1);

    fetch(`${this.props.apiROOT}post/insertPoll`, {
      method: "post",
      body: formdata
    })
      .then(res => res.json())
      .then(data => {
        if (data.status === true) {
          let alertElem = document.querySelector("#alert");
          this.alertMsg(
            alertElem,
            "success-alert",
            "Poll posted successfully!"
          );
          setTimeout(function() {
            let elem = document.querySelector(".poll-editor-wrapper");
            elem.classList.add("hide");
            elem.classList.remove("visible");
          }, 2000);
        }
      });
  }

  render() {
    const days = [];
    const hours = [];
    const minutes = [];
    for (let i = 0; i < 7; i++) {
      days.push(<option key={i}> {i}</option>);
    }
    for (let i = 0; i < 24; i++) {
      hours.push(<option key={i}> {i}</option>);
    }
    for (let i = 0; i < 60; i++) {
      minutes.push(<option key={i}> {i}</option>);
    }

    return (
      <form className="editor shadow" onSubmit={this.onSubmit}>
        <small id="alert" />

        <div className="input-wrapper">
          <span className="char-counter" style={{ display: "none" }} />
          <select name="post_int" className="select" onChange={this.onChange}>
            <option> Pick an interest </option>
            {this.state.userInterests.map(u => (
              <option key={u.interest}> {u.interest} </option>
            ))}
          </select>
        </div>

        <div className="input-wrapper">
          <textarea
            onChange={this.onChange}
            name="post_title"
            rows="3"
            placeholder="Question..."
            maxLength="200"
          />
          <p className="char-counter-wrapper">
            <span className="char-counter">0 </span> / <span> 200</span>
          </p>
        </div>

        <div className="input-wrapper">
          <input
            onChange={this.onChange}
            className="option"
            type="text"
            name="option_one"
            maxLength="100"
            placeholder="Option 1"
          />
          <p className="char-counter-wrapper">
            <span className="char-counter">0 </span> / <span>100</span>
          </p>
        </div>

        <div className="input-wrapper">
          <input
            onChange={this.onChange}
            className="option"
            type="text"
            name="option_two"
            maxLength="100"
            placeholder="Option 2"
          />
          <p className="char-counter-wrapper">
            <span className="char-counter">0 </span> / <span>100</span>
          </p>
        </div>

        {this.state.count > 2 && (
          <div className="input-wrapper">
            <input
              onChange={this.onChange}
              className="option"
              type="text"
              name="option_three"
              maxLength="100"
              placeholder="Option 3"
            />
            <p className="char-counter-wrapper">
              <span className="char-counter">0 </span> / <span>100</span>
            </p>
          </div>
        )}

        {this.state.count > 3 && (
          <div className="input-wrapper">
            <input
              onChange={this.onChange}
              className="option"
              type="text"
              name="option_four"
              maxLength="100"
              placeholder="Option 4"
            />
            <p className="char-counter-wrapper">
              <span className="char-counter">0 </span> / <span>100</span>
            </p>
          </div>
        )}

        <div className="counter">
          <span className="btn" onClick={this.addOptions}>
            {" "}
            +{" "}
          </span>
          <span className="btn" onClick={this.removeOptions}>
            {" "}
            -{" "}
          </span>
        </div>

        <div className="poll-editor-duration">
          <div className="poll-editor-mins">
            <label>Mins</label>
            <select name="poll_editor_mins" onChange={this.onChange}>
              {minutes}
            </select>
          </div>

          <div className="poll-editor-hours">
            <label>Hours</label>
            <select name="poll_editor_hours" onChange={this.onChange}>
              {hours}
            </select>
          </div>

          <div className="poll-editor-days">
            <label>Days</label>
            <select name="poll_editor_days" onChange={this.onChange}>
              {days}
            </select>
          </div>
        </div>

        <div className="input-wrapper">
          <button type="submit" className="btn">
            Start Poll
          </button>
        </div>
      </form>
    );
  }
}

export default PollEditor;
