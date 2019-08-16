import React, { Component } from "react";

class ReportEditor extends Component {
  constructor(props) {
    super(props);
    this.state = {
      report: "",
      ref: "",
      csrf_token: ""
    };

    this.onSubmit = this.onSubmit.bind(this);
    this.onChange = this.onChange.bind(this);
    this.alertMsg = this.alertMsg.bind(this);
    this.goBackToLastUrl = this.goBackToLastUrl.bind();
  }

  componentDidMount() {
    let commentID =
      this.props.match.params.commentID === undefined
        ? 0
        : this.props.match.params.commentID;
    let subcommentID =
      this.props.match.params.subcommentID === undefined
        ? 0
        : this.props.match.params.subcommentID;

    fetch(
      `${this.props.apiROOT}post/report/${this.props.match.params.reportTag}/${
        this.props.match.params.postID
      }/${commentID}/${subcommentID}`
    )
      .then(res => res.json())
      .then(data => {
        this.setState({
          ref: data.ref,
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
  }

  onSubmit(e) {
    e.preventDefault();

    if (this.state.report.trim().length < 5) {
      let alertElem = document.querySelector("#alert");
      this.alertMsg(alertElem, "error-alert", "Report is too short");
      return;
    }

    let formdata = new FormData();
    formdata.append("report", this.state.report);
    formdata.append("csrf_token", this.state.csrf_token);

    let commentID =
      this.props.match.params.commentID === undefined
        ? 0
        : this.props.match.params.commentID;
    let subcommentID =
      this.props.match.params.subcommentID === undefined
        ? 0
        : this.props.match.params.subcommentID;

    fetch(
      `${this.props.apiROOT}post/report/${this.props.match.params.reportTag}/${
        this.props.match.params.postID
      }/${commentID}/${subcommentID}`,
      {
        method: "post",
        body: formdata
      }
    )
      .then(res => res.json())
      .then(data => {
        if (data.status === true) {
          let alertElem = document.querySelector("#alert");
          this.alertMsg(alertElem, "success-alert", "Report sent!");
          setTimeout(function() {
            window.history.back();
          }, 2000);
        }
      });
  }

  goBackToLastUrl() {
    window.history.back();
  }

  render() {
    return (
      <div className="pg-edit-form">
        <button
          className="btn shadow pg-edit-form-back-btn"
          onClick={this.goBackToLastUrl}
        >
          Back
        </button>
        <form className="shadow" onSubmit={this.onSubmit}>
          <small id="alert" />
          <div className="reference">
            <p>{this.state.ref}</p>
          </div>

          <div className="input-wrapper">
            <textarea
              name="report"
              rows="10"
              placeholder="Type your report..."
              onChange={this.onChange}
            />
          </div>

          <div className="input-wrapper">
            <input
              type="submit"
              className="pg-edit-form-submit btn"
              value="Submit Report"
            />
          </div>
        </form>
      </div>
    );
  }
}

export default ReportEditor;
