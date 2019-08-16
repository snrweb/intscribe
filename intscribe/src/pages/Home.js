import React, { Component } from "react";
import { ReactComponent as ArticleSVG } from "../images/svg/article.svg";
import { ReactComponent as QuestionSVG } from "../images/svg/question.svg";
import { ReactComponent as PollSVG } from "../images/svg/poll.svg";
import Posts from "../components/Posts";
import ColumnOne from "../components/ColumOne";
import ColumnThree from "../components/ColumnThree";
import { Store } from "../store/Store";

class Home extends Component {
  constructor(props) {
    super(props);

    this.state = {
      isLoggedInUserID: 1,
      isLoggedIn: true,
      posts: [],
      pageName: "",
      requestSent: false,
      counter: 3,
      action: "homePost", //A method in Post Controller in php
      hasFetchedAllPost: false,
      scrollTo: 0
    };

    this.beforeListFetch = this.beforeListFetch.bind(this);
    this.fetchPostList = this.fetchPostList.bind(this);
    this.fetchNonInterestPostList = this.fetchNonInterestPostList.bind(this);
    this.setScrollPosition = this.setScrollPosition.bind(this);
  }

  beforeListFetch() {
    if (this.state.requestSent) return;

    setTimeout(this.fetchPostList, 2000);
    this.setState({ requestSent: true });
  }

  fetchPostList() {
    if (this.state.hasFetchedAllPost) return;
    fetch(
      `${this.props.apiROOT}post/${this.state.action}/${this.state.counter}`
    )
      .then(res => res.json())
      .then(data => {
        this.setState({
          posts: this.state.posts.concat(data.posts)
        });

        if (data.posts.length < 3) {
          if (this.state.action === "homePost") {
            this.setState({
              requestSent: false,
              counter: 0,
              action: "otherPost"
            });
          } else if (this.state.action === "otherPost") {
            this.setState({
              requestSent: false,
              counter: this.state.counter + 3,
              hasFetchedAllPost: true
            });
          }
        } else {
          this.setState({
            requestSent: false,
            counter: this.state.counter + 3
          });
        }
      });
  }

  //fetch posts that are not part of users interests
  fetchNonInterestPostList() {
    fetch(`${this.props.apiROOT}post/otherPost/0`)
      .then(res => res.json())
      .then(data => {
        this.setState({
          posts: this.state.posts.concat(data.posts),
          requestSent: false,
          action: "otherPost"
        });
      });
  }

  setScrollPosition() {
    let position = window.pageYOffset;
    this.setState({ scrollTo: position });
  }

  componentDidMount() {
    if ("homeState" in Store) {
      setTimeout(function() {
        window.scrollTo(0, Store.homeState.scrollTo);
      }, 200);
    }

    window.addEventListener("scroll", this.setScrollPosition);

    if ("homeState" in Store) {
      this.setState({
        isLoggedIn: Store.homeState["isLoggedIn"],
        posts: Store.homeState["posts"],
        pageName: Store.homeState["pageName"],
        requestSent: Store.homeState["requestSent"],
        counter: Store.homeState["counter"],
        action: Store.homeState["action"],
        hasFetchedAllPost: Store.homeState["hasFetchedAllPost"],
        scrollTo: Store.homeState["scrollTo"]
      });
    } else {
      //fetch posts based on users interests
      //and if there are no more post based on interest,
      //other posts are selected
      fetch(`${this.props.apiROOT}post/homePost/0`)
        .then(res => res.json())
        .then(data => {
          this.setState({
            posts: data.posts,
            pageName: data.pageName
          });
          if (data.posts.length < 1) {
            this.fetchNonInterestPostList();
          } else {
            this.setState({
              requestSent: false
            });
          }
        });
    }
  }

  componentWillUnmount() {
    Store.homeState = this.state;
    window.removeEventListener("scroll", this.setScrollPosition);
  }

  render() {
    return (
      <React.Fragment>
        <ColumnOne />
        <div className="columnTwo">
          <div className="c2-editor-buttons">
            <p
              className="c2-article-button"
              onClick={this.props.toggleArticleEditor}
            >
              <ArticleSVG />
              <span>Article</span>
            </p>

            <p
              className="c2-question-button"
              onClick={this.props.toggleQuestionEditor}
            >
              <QuestionSVG />
              <span>Question</span>
            </p>

            <p className="c2-poll-button" onClick={this.props.togglePollEditor}>
              <PollSVG />
              <span>Poll</span>
            </p>
          </div>
          <Posts
            isLoggedIn={this.state.isLoggedIn}
            isLoggedInUserID={this.state.isLoggedInUserID}
            posts={this.state.posts}
            pageName={this.state.pageName}
            apiROOT={this.props.apiROOT}
            root={this.props.root}
            beforeListFetch={this.beforeListFetch}
          />
        </div>
        <ColumnThree apiROOT={this.props.apiROOT} />
      </React.Fragment>
    );
  }
}

export default Home;
