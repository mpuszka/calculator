var gulp      = require('gulp');
var sass      = require('gulp-sass');
var cleanCSS  = require('gulp-clean-css');

gulp.task('sass', async () => {
    gulp.src('./assets/style.scss')
    .pipe(sass()) 
    .pipe(cleanCSS({compatibility: 'ie8'}))
    .pipe(gulp.dest('./assets'))
});

gulp.task('default', gulp.parallel('sass'));
