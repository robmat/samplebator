#import <UIKit/UIKit.h>
#import "CommonViewControllerBase.h"

@interface TextInputViewController : CommonViewControllerBase {
	
	IBOutlet UITextView* textView;
	IBOutlet UITextField* targetTextView;

}

@property (nonatomic, retain) UITextView* textView;
@property (nonatomic, retain) UITextField* targetTextView;

- (IBAction) okAction: (id) sender;

@end
