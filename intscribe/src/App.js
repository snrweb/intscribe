import React, { Component } from "react";
import { BrowserRouter as Router, Route } from "react-router-dom";
import { ROOT, apiROOT } from "./config/config";
import NavBar from "./components/NavBar";
import Home from "./pages/Home";
import InterestPosts from "./pages/InterestPosts";
import Notification from "./pages/Notification";
import User from "./pages/User";
import MainPost from "./pages/MainPost";
import ReportEditor from "./editors/ReportEditor";
import PollEditor from "./editors/PollEditor";
import { ReactComponent as CloseSVG } from "./images/svg/close.svg";
import QuestionEditor from "./editors/QuestionEditor";
import ArticleEditor from "./editors/ArticleEditor";

class App extends Component {
  constructor(props) {
    super(props);

    this.state = {
      isLoggedIn: false,
      isLoggedInUserID: 0
    };
  }

  componentDidMount() {
    fetch(`${apiROOT}nav`)
      .then(res => res.json())
      .then(data => {
        if (data.loggedUser.user_id > 0) {
          this.setState({
            isLoggedIn: true,
            isLoggedInUserID: parseInt(data.loggedUser.user_id)
          });
        }
      });
  }

  togglePollEditor = () => {
    let elem = document.querySelector(".poll-editor-wrapper");
    if (elem.classList.contains("hide")) {
      elem.classList.remove("hide");
      elem.classList.add("visible");
    } else {
      elem.classList.add("hide");
      elem.classList.remove("visible");
    }
  };

  toggleQuestionEditor = () => {
    let elem = document.querySelector(".question-editor-wrapper");
    if (elem.classList.contains("hide")) {
      elem.classList.remove("hide");
      elem.classList.add("visible");
    } else {
      elem.classList.add("hide");
      elem.classList.remove("visible");
    }
  };

  toggleArticleEditor = () => {
    let editor = document.getElementById("article_editor_frame");
    editor.contentWindow.document.designMode = "On";

    let elem = document.querySelector(".article-editor-wrapper");
    if (elem.classList.contains("hide")) {
      elem.classList.remove("hide");
      elem.classList.add("visible");
    } else {
      elem.classList.add("hide");
      elem.classList.remove("visible");
    }
  };

  render() {
    return (
      <React.Fragment>
        <Router basename="/">
          <NavBar
            root={ROOT}
            apiROOT={apiROOT}
            isLoggedIn={this.state.isLoggedIn}
            isLoggedInUserID={this.state.isLoggedInUserID}
          />
          <div className="App" id="intscribe">
            <Route
              exact
              path="/"
              render={props => (
                <Home
                  {...props}
                  root={ROOT}
                  apiROOT={apiROOT}
                  togglePollEditor={this.togglePollEditor}
                  toggleQuestionEditor={this.toggleQuestionEditor}
                  toggleArticleEditor={this.toggleArticleEditor}
                />
              )}
            />

            <Route
              exact
              path="/notification"
              render={props => (
                <Notification {...props} root={ROOT} apiROOT={apiROOT} />
              )}
            />

            <Route
              exact
              path="/interest/:interestName"
              render={props => (
                <InterestPosts {...props} root={ROOT} apiROOT={apiROOT} />
              )}
            />

            <Route
              exact
              path="/user/:user"
              render={props => (
                <User
                  {...props}
                  root={ROOT}
                  apiROOT={apiROOT}
                  isLoggedIn={this.state.isLoggedIn}
                  isLoggedInUserID={this.state.isLoggedInUserID}
                />
              )}
            />

            <Route
              exact
              path="/user/:user/:suser"
              render={props => (
                <User
                  {...props}
                  root={ROOT}
                  apiROOT={apiROOT}
                  isLoggedIn={this.state.isLoggedIn}
                  isLoggedInUserID={this.state.isLoggedInUserID}
                />
              )}
            />

            <Route
              exact
              path="/post/:title"
              render={props => (
                <MainPost {...props} root={ROOT} apiROOT={apiROOT} />
              )}
            />

            <Route
              exact
              path="/post/report/:reportTag/:postID"
              render={props => (
                <ReportEditor {...props} apiROOT={apiROOT} root={ROOT} />
              )}
            />

            <Route
              exact
              path="/post/report/:reportTag/:postID/:commentID"
              render={props => (
                <ReportEditor {...props} apiROOT={apiROOT} root={ROOT} />
              )}
            />

            <Route
              path="/post/report/:reportTag/:postID/:commentID/:subcommentID"
              render={props => (
                <ReportEditor {...props} apiROOT={apiROOT} root={ROOT} />
              )}
            />

            <div className="article-editor-wrapper hide">
              <span
                className="article-editor-wrapper-close"
                onClick={this.toggleArticleEditor}
              >
                <CloseSVG /> Close
              </span>

              <ArticleEditor
                apiROOT={apiROOT}
                root={ROOT}
                post_title=""
                post_int=""
                setter="insert"
              />
            </div>

            <div className="poll-editor-wrapper hide">
              <span
                className="poll-editor-wrapper-close"
                onClick={this.togglePollEditor}
              >
                <CloseSVG /> Close
              </span>
              <PollEditor apiROOT={apiROOT} root={ROOT} />
            </div>

            <div className="question-editor-wrapper hide">
              <span
                className="question-editor-wrapper-close"
                onClick={this.toggleQuestionEditor}
              >
                <CloseSVG /> Close
              </span>
              <QuestionEditor apiROOT={apiROOT} root={ROOT} />
            </div>
          </div>
        </Router>
      </React.Fragment>
    );
  }
}

export default App;
