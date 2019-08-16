import React, { Component } from "react";
import Posts from "../components/Posts";
import ColumnOne from "../components/ColumOne";
import ColumnThree from "../components/ColumnThree";

//An object to hold the state properties and values of this component
import { Store } from "../store/Store";

class InterestPosts extends Component {
  constructor(props) {
    super(props);

    this.state = {
      isLoggedInUserID: 1,
      isLoggedIn: true,
      posts: [],
      pageName: "",
      requestSent: false,
      hasFetchedAllPost: false,
      counter: 10,
      scrollTo: 0
    };

    this.beforeListFetch = this.beforeListFetch.bind(this);
    this.fetchPostList = this.fetchPostList.bind(this);
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
      `${this.props.apiROOT}post/interestPost/${
        this.props.match.params.interestName
      }/${this.state.counter}`
    )
      .then(res => res.json())
      .then(data => {
        this.setState({
          posts: this.state.posts.concat(data.posts)
        });
        if (data.posts.length > 0) {
          this.setState({
            requestSent: false,
            counter: this.state.counter + 10
          });
        }

        if (data.posts.length < 10) {
          this.setState({
            hasFetchedAllPost: true
          });
        }
      });
  }

  setScrollPosition() {
    let position = window.pageYOffset;
    this.setState({ scrollTo: position });
  }

  componentDidMount() {
    let interest = this.props.match.params.interestName;

    window.addEventListener("scroll", this.setScrollPosition);

    if (interest in Store) {
      setTimeout(function() {
        window.scrollTo(0, Store.homeState.scrollTo);
      }, 300);
    }

    if (interest in Store) {
      this.setState({
        isLoggedIn: Store[interest]["isLoggedIn"],
        posts: Store[interest]["posts"],
        pageName: Store[interest]["pageName"],
        requestSent: Store[interest]["requestSent"],
        counter: Store[interest]["counter"],
        isLoggedInUserID: Store[interest]["isLoggedInUserID"],
        hasFetchedAllPost: Store[interest]["hasFetchedAllPost"],
        scrollTo: Store[interest]["scrollTo"]
      });
    } else {
      fetch(
        `${this.props.apiROOT}post/interestPost/${
          this.props.match.params.interestName
        }/0`
      )
        .then(res => res.json())
        .then(data => {
          this.setState({
            posts: data.posts,
            pageName: data.pageName
          });
        });
    }
  }

  componentWillUnmount() {
    Store[this.props.match.params.interestName] = this.state;
    window.removeEventListener("scroll", this.setScrollPosition);
  }

  componentWillUpdate(nextProp) {
    if (
      this.props.match.params.interestName !==
      nextProp.match.params.interestName
    ) {
      const { interestName } = nextProp.match.params;
      fetch(`${this.props.apiROOT}post/interestPost/${interestName}`)
        .then(res => res.json())
        .then(data => {
          this.setState({
            posts: data.posts,
            pageName: data.pageName
          });
        });
    }
  }

  render() {
    return (
      <React.Fragment>
        <ColumnOne />
        <div className="columnTwo">
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

export default InterestPosts;
