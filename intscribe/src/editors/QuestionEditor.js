import React, { Component } from "react";

class QuestionEditor extends Component {
  constructor(props) {
    super(props);
    this.state = {
      userInterests: [],
      post_title: "",
      post_int: "",
      link: "",
      csrf_token: ""
    };

    this.onSubmit = this.onSubmit.bind(this);
    this.onChange = this.onChange.bind(this);
    this.alertMsg = this.alertMsg.bind(this);
  }

  componentDidMount() {
    fetch(`${this.props.apiROOT}post/insertQuestion`)
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

  onChange(e) {
    this.setState({ [e.target.name]: e.target.value });
    e.target.parentElement.querySelector(".char-counter").innerHTML =
      e.target.value.length;
  }

  onSubmit(e) {
    e.preventDefault();
    let alertElem = document.querySelector("#alertQ");

    if (this.state.post_title.trim().length < 10) {
      this.alertMsg(alertElem, "error-alert", "Question is too short");
      return;
    }

    if (this.state.post_title.trim().length > 200) {
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
      this.alertMsg(alertElem, "error-alert", "Select interest");
      return;
    }

    this.alertMsg(alertElem, "success-alert", "Please wait...");

    let formdata = new FormData();
    formdata.append("post_title", this.state.post_title);
    formdata.append("post_int", this.state.post_int);
    formdata.append("question_link", this.state.link);
    formdata.append("csrf_token", this.state.csrf_token);
    formdata.append("isJSeditor", 1);

    fetch(`${this.props.apiROOT}post/insertQuestion`, {
      method: "post",
      body: formdata
    })
      .then(res => res.json())
      .then(data => {
        if (data.status === true) {
          let alertElem = document.querySelector("#alertQ");
          this.alertMsg(
            alertElem,
            "success-alert",
            "Question posted successfully!"
          );
          setTimeout(function() {
            let elem = document.querySelector(".question-editor-wrapper");
            elem.classList.add("hide");
            elem.classList.remove("visible");
          }, 2000);
        }
      });
  }

  render() {
    return (
      <form className="editor shadow" onSubmit={this.onSubmit}>
        <small id="alertQ" />

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
            type="url"
            name="link"
            maxLength="100"
            placeholder="Link (optional, https://www.intscribe.com)"
            title="URL format: https://www.intscribe.com"
          />
          <p className="char-counter-wrapper">
            <span className="char-counter">0 </span> / <span> 100</span>
          </p>
        </div>

        <div className="input-wrapper">
          <button type="submit" className="btn">
            Submit Question
          </button>
        </div>
      </form>
    );
  }
}

export default QuestionEditor;
