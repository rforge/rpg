% Generated by roxygen2 (4.1.0): do not edit by hand
% Please edit documentation in R/rpg.R
\docType{package}
\name{rpg-package}
\alias{rpg}
\alias{rpg-package}
\title{Easy Access to Advanced PostgreSQL Features}
\description{
Provides functions for connecting to, reading from and writing to a PostgreSQL
database. Facilities for tracing the communication between R and PostgreSQL are
provided, as are function to retieve detailed session metadata.
}
\details{
\tabular{ll}{
Package: \tab rpg\cr
Type: \tab Package\cr
Version: \tab 1.4\cr
Date: \tab 2015-2-21\cr
License: \tab GPL \cr
}
The main functions are \code{connect}, which establishes a connection,
\code{query}, which issues queries and \code{fetch}, which retieves results.
Intelligent defaults are used throughout. Functions that require a connection
will automatically attempt to establish a valid connection based on a previous
connection or from defaults. The defaults can be overriden in a variety of
ways.
}
\author{
Timothy H. Keitt \cr \url{http://www.keittlab.org/} \cr \cr
Maintainer: Timothy H. Keitt \email{tkeitt@gmail.com} \cr
}
\references{
\url{http://github.com/thk686/rpg}, \url{http://www.postgresql.org/}
}
\keyword{package}

