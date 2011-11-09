#import <UIKit/UIKit.h>
#import "VCBase.h"

@interface WebViewVC : VCBase <UIWebViewDelegate> {
	IBOutlet UIWebView* webView;
	NSString* url;
	IBOutlet UIActivityIndicatorView* indicator;
    IBOutlet UIImageView* bottomBar;
    IBOutlet UIImageView* blankBar;
@public
	BOOL dontAppendPass;
}

@property (nonatomic, retain) IBOutlet UIWebView* webView;
@property (nonatomic, retain) NSString* url;
@property (nonatomic, retain) IBOutlet UIActivityIndicatorView* indicator;
@property (nonatomic, retain) IBOutlet UIImageView* bottomBar;
@property (nonatomic, retain) IBOutlet UIImageView* blankBar;

- (void)setUpViewByOrientation: (UIInterfaceOrientation)toInterfaceOrientation;

@end
