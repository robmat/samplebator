
//Copyright Applicable Ltd 2011

#import <UIKit/UIKit.h>

@interface EGOTextFieldAlertView : UIAlertView {
@private
	NSMutableArray* __textFields; // Single underscore is used in UIAlertView
	BOOL overrodeHeight;
	CGFloat textFieldHeightOffset;
}

//
// All of the methods below are intentionally named differently
// than the Apple private methods, as to not cause any false rejections
//

- (void)addTextField:(UITextField*)textField;
- (UITextField*)addTextFieldWithLabel:(NSString*)label;
- (UITextField*)addTextFieldWithLabel:(NSString*)label value:(NSString*)value;

- (UITextField*)textFieldForIndex:(NSInteger)index;

@property(nonatomic,readonly) NSInteger numberOfTextFields;
@property(nonatomic,readonly) UITextField* firstTextField;
@end