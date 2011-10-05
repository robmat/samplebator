#import <UIKit/UIKit.h>
#import "VCBase.h"

@interface CompetitorsVC : VCBase <UIWebViewDelegate> {
	IBOutlet UIWebView* webView;
}

@property(retain,nonatomic) IBOutlet UIWebView* webView;

@end
